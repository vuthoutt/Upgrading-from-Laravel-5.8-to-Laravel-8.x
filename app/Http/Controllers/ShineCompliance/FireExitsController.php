<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\FireExitsService;
use App\Services\ShineCompliance\PropertyService;
use App\Http\Request\ShineCompliance\Assessment\FireExitRequest;
use Illuminate\Http\Request;

class FireExitsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $fireExitsService;
    private $assessmentService;
    private $propertyService;

    public function __construct(
        AssessmentService $assessmentService,
        PropertyService $propertyService,
        FireExitsService $fireExitsService
    )
    {
        $this->assessmentService = $assessmentService;
        $this->propertyService = $propertyService;
        $this->fireExitsService = $fireExitsService;
    }

    public function getAddFireExit($property_id, Request $request)
    {
        $assess_id = 0;
        $assessment = null;
        if ($request->has('assess_id')) {
            $assess_id = $request->assess_id;
            $assessment = $this->assessmentService->getAssessmentDetail($assess_id);
        }
        $property =  $this->propertyService->getProperty($property_id);

        $areas = $this->assessmentService->getAreaAssessment($assess_id, $property_id);
        $reasons = $this->assessmentService->getEquipmentDropdownData(EQUIPMENT_REASON_INACCESS);
        $fireExitTypes = [
            (object)[
                'id' => FIRE_EXIT_TYPE_FINAL,
                'description' => 'Final Exit',
            ],
            (object)[
                'id' => FIRE_EXIT_TYPE_STOREY,
                'description' => 'Storey Exit',
            ],
        ];

        //log audit
        $comment = \Auth::user()->full_name . " viewed add fire exit form for property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(FIRE_EXIT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.fireExist.get_add_fire_exit', compact(
            'areas',
            'property',
            'assessment',
            'assess_id',
            'property_id',
            'reasons',
            'fireExitTypes'));
    }

    public function postAddFireExit(FireExitRequest $request)
    {

        $result = $this->fireExitsService->updateOrCreateFireExit($request->validated());
        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                //todo redirect to detail
                return redirect()->route('shineCompliance.assessment.get_fire_exit',['id' => $result['data']->id ?? 0])->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function getFireExit($fire_exit_id)
    {
        if(!$fireExit = $this->fireExitsService->getFireExitDetail($fire_exit_id)){
            abort(404);
        }

        $can_update  = true;
        if (!\CommonHelpers::isSystemClient() and ($fireExit->assess_id == 0)) {
            $can_update  = false;
        } elseif(\CommonHelpers::isSystemClient() and ($fireExit->assess_id == 0)) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_FIRE_EXITS_ASSEMBLY_POINTS) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $fireExit->property_id)) {
                $can_update = false;
            }
        }

        return view('shineCompliance.fireExist.get_fire_exit', compact('fireExit','can_update'));
    }

    public function getEditFireExit($fire_exit_id)
    {
        if(!$fireExit = $this->fireExitsService->getFireExitDetail($fire_exit_id)){
            abort(404);
        }
        $areas = $this->assessmentService->getAreaAssessment($fireExit->assess_id, $fireExit->property_id);
        $locations = $this->assessmentService->getLocationsByAssessmentAndArea($fireExit->assess_id, $fireExit->area_id);
        $reasons = $this->assessmentService->getEquipmentDropdownData(EQUIPMENT_REASON_INACCESS);
        $fireExitTypes = [
            (object)[
                'id' => FIRE_EXIT_TYPE_FINAL,
                'description' => 'Final Exit',
            ],
            (object)[
                'id' => FIRE_EXIT_TYPE_STOREY,
                'description' => 'Storey Exit',
            ],
        ];

        //log audit
        $comment = \Auth::user()->full_name . " viewed fire exit to edit";
        \ComplianceHelpers::logAudit(FIRE_EXIT_TYPE, $fireExit->id, AUDIT_ACTION_VIEW, $fireExit->reference, $fireExit->property_id, $comment);

        return view('shineCompliance.fireExist.get_edit_fire_exit', compact('fireExit',
            'areas',
            'locations',
            'reasons',
            'fireExitTypes'));
    }

    public function postEditFireExit($fire_exit_id, FireExitRequest $request)
    {
        if(!$fireExit = $this->fireExitsService->getFireExitDetail($fire_exit_id)){
            abort(404);
        }

        $result = $this->fireExitsService->updateOrCreateFireExit($request->validated(), $fire_exit_id);
        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                //todo redirect to detail
                return redirect()->route('shineCompliance.assessment.get_fire_exit',['id' => $result['data']->id ?? 0])->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postDecommissionFireExit($fire_exit_id,Request $request)
    {
        if(!$fireExit = $this->fireExitsService->getFireExitDetail($fire_exit_id)){
            abort(404);
        }
        $reason_decommissioned = $request->get('decommission_reason');
        $result = $this->fireExitsService->decommissionFireExit($fireExit, $reason_decommissioned);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postRecommissionFireExit($fire_exit_id)
    {
        if(!$fireExit = $this->fireExitsService->getFireExitDetail($fire_exit_id)){
            abort(404);
        }
        $result = $this->fireExitsService->recommissionFireExit($fireExit);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }
}
