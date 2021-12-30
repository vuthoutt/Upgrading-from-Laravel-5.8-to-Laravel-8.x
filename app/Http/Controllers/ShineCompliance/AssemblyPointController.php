<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\AssemblyPointService;
use App\Services\ShineCompliance\PropertyService;
use App\Http\Request\ShineCompliance\Assessment\AssemblyPointRequest;
use Illuminate\Http\Request;

class AssemblyPointController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $assemblyPointService;
    private $assessmentService;
    private $propertyService;

    public function __construct(
        AssessmentService $assessmentService,
        PropertyService $propertyService,
        AssemblyPointService $assemblyPointService
    )
    {
        $this->assessmentService = $assessmentService;
        $this->propertyService = $propertyService;
        $this->assemblyPointService = $assemblyPointService;
    }


    public function getAddAssemblyPoint($property_id, Request $request)
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

        return view('shineCompliance.assemblyPoint.get_add_assembly_point', compact('assessment','property',
            'areas',
            'property_id',
            'assess_id',
            'reasons'));
    }

    public function postAddAssemblyPoint($assess_id, AssemblyPointRequest $request)
    {
        $result = $this->assemblyPointService->updateOrCreateAssemblyPoint($request->validated());
        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                // redirect to detail
                return redirect()->route('shineCompliance.assessment.get_assembly_point',['id' => $result['data']->id ?? 0])->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function getAssemblyPoint($assembly_point_id)
    {
        if(!$assemblyPoint = $this->assemblyPointService->getAssemblyDetail($assembly_point_id)){
            abort(404);
        }

        $can_update  = true;
        if (!\CommonHelpers::isSystemClient() and ($assemblyPoint->assess_id == 0)) {
            $can_update  = false;
        } elseif(\CommonHelpers::isSystemClient() and ($assemblyPoint->assess_id == 0)) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_FIRE_EXITS_ASSEMBLY_POINTS)|| !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $assemblyPoint->property_id)) {
                $can_update = false;
            }
        }

        // log audit
        $comment = \Auth::user()->full_name . " viewed assembly point detail";
        \ComplianceHelpers::logAudit(ASSEMBLY_POINT, $assemblyPoint->id, AUDIT_ACTION_VIEW, $assemblyPoint->reference, $assemblyPoint->property_id, $comment);

        return view('shineCompliance.assemblyPoint.get_assembly_point', compact('assemblyPoint','can_update'));
    }

    public function getEditAssemblyPoint($assembly_point_id)
    {
        if(!$assemblyPoint = $this->assemblyPointService->getAssemblyDetail($assembly_point_id)){
            abort(404);
        }
        $areas = $this->assessmentService->getAreaAssessment($assemblyPoint->assess_id,  $assemblyPoint->property_id);
        $locations = $this->assessmentService->getLocationsByAssessmentAndArea($assemblyPoint->assess_id, $assemblyPoint->area_id);
        $reasons = $this->assessmentService->getEquipmentDropdownData(EQUIPMENT_REASON_INACCESS);

        return view('shineCompliance.assemblyPoint.get_edit_assembly_point', compact('assemblyPoint',
            'areas',
            'locations',
            'reasons'));
    }

    public function postEditAssemblyPoint($assembly_point_id, AssemblyPointRequest $request)
    {
        if(!$assemblyPoint = $this->assemblyPointService->getAssemblyDetail($assembly_point_id)){
            abort(404);
        }

        $result = $this->assemblyPointService->updateOrCreateAssemblyPoint($request->validated(), $assembly_point_id);
        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                //todo redirect to detail
                return redirect()->route('shineCompliance.assessment.get_assembly_point',['id' => $assembly_point_id])->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postDecommissionAssemblyPoint($assembly_point_id, Request $request)
    {
        if(!$assemblyPoint = $this->assemblyPointService->getAssemblyDetail($assembly_point_id)){
            abort(404);
        }
        $reason_decommissioned = $request->get('decommission_reason');
        $result = $this->assemblyPointService->decommissionAssembly($assemblyPoint,$reason_decommissioned);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postRecommissionAssemblyPoint($assembly_point_id)
    {
        if(!$assemblyPoint = $this->assemblyPointService->getAssemblyDetail($assembly_point_id)){
            abort(404);
        }
        $result = $this->assemblyPointService->recommissionAssembly($assemblyPoint);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }
}
