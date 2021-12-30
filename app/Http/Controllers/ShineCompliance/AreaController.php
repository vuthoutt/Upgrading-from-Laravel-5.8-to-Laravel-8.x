<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\AreaService;
use Illuminate\Http\Request;
use App\Http\Request\Client\UpdateOrganisationRequest;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    private $areaService;
    private $propertyService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AreaService $areaService,
        PropertyService $propertyService
    )
    {
        $this->areaService = $areaService;
        $this->propertyService = $propertyService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index(Request $request)
    {
//        if ($request->has('zone_id')) {
//            return $this->indexOperative($request, $request->zone_id);
//        } else {
//            return $this->indexOperative($request);
//        }
    }

    public function listArea($id, Request $request){
        $property = $this->propertyService->getProperty($id);
        $area = $this->areaService->getAllArea($id, $request);
        $data_reason = $this->areaService->getDropDownReasonArea();

        $can_add_new = true;

        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } else if (\CommonHelpers::isClientUser()) {
                if ($property->client_id != \Auth::user()->client_id) {
                    $can_add_new = false;
                }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_AREA_FLOOR) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $id)) {
                $can_add_new = false;
            }
        }
        //todo search ajax
        if($request->ajax()){
            return view('shineCompliance.properties.area.area',['data_area' => $area,
                'property_id' => $id,
                'property' => $property,
                'can_add_new' => $can_add_new,
                'data_reason' => $data_reason ])->render();
        }
        // audit
        $comment = \Auth::user()->full_name . " viewed list area on property" . $property->name;
        \ComplianceHelpers::logAudit(AREA_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->id, $comment);
        if($area){
            return view('shineCompliance.properties.area.area',['data_area' => $area,
                'property_id' => $id,
                'property' => $property,
                'can_add_new' => $can_add_new,
                'data_reason' => $data_reason ]);
        }
        abort(404);
    }

    public function detail($property_id, $area_id){

        if(isset($property_id) && isset($area_id)){
            $area = $this->areaService->getAreaDetail($area_id);
            $area_stamping  = \CommonHelpers::get_data_stamping($area);
            $data_reason = $this->areaService->getDropDownReasonArea();

            $can_update  = true;

            if (!\CommonHelpers::isSystemClient()) {
                $can_update = false;
            } else if (\CommonHelpers::isClientUser()) {
                    if ($property->client_id != \Auth::user()->client_id) {
                        $can_update = false;
                    }
            } else {
                // check view register area permission
                if(!\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_AREA_FLOORS)) {
                    abort(401);
                }
                // check update permission
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_AREA_FLOOR) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $area->property_id)) {
                    $can_update = false;
                }
            }
            // audit
            $comment = \Auth::user()->full_name . " viewed area detail page " . $area->reference;
            \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_VIEW, $area->reference, $area->id, $comment);
            if($area){
                return view('shineCompliance.properties.area.detail',['area' => $area,'property_id' => $property_id,'can_update' => $can_update,
                    'area_stamping'=> $area_stamping,'data_reason'=>$data_reason]);
            }
        }
        abort(404);
    }

    public function createArea(Request $request) {

        $validator = \Validator::make($request->all(), [
            'survey_id' => 'required',
            'area_reference' => 'required|max:255',
            'description' => 'required|max:255',
        ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        if($request->has('area_id')) {
            $id = $request->area_id;
            $dataUpdate = [
                'area_reference' => $request->area_reference,
                'description' => $request->description,
                'state' => $request->accessibility ?? 2,
            ];
            if(!$request->has('accessibility')){
                $dataUpdate['reason'] = $request->inreason ?? '';
            }

            if($request->has('comment')){
                $dataUpdate['reason_area'] = $request->comment ?? '';
            }

            $createArea = $this->areaService->updateArea($id, $dataUpdate);
        } else {
            $dataCreate = [
                'property_id' => $request->property_id,
                'survey_id' => $request->survey_id,
                'area_reference' => $request->area_reference,
                'description' => $request->description,
                'state' => $request->accessibility ?? 2,
                'decommissioned' => 0
            ];
            if(!$request->has('accessibility')){
                $dataCreate['reason'] = $request->inreason ?? '';
            }

            if($request->has('comment')){
                $dataCreate['reason_area'] = $request->comment ?? '';
            }

            $createArea = $this->areaService->createArea($dataCreate);
        }
        if (isset($createArea) and !is_null($createArea)) {
            \Session::flash('msg', $createArea['msg']);
            return response()->json(['status_code' => $createArea['status_code'], 'success'=> $createArea['msg'], 'id' => $createArea['data'] ?? '']);
        }
    }

    public function decommissionArea($area_id, Request $request) {

        $reason = $request->area_decommisson_reason_add ?? '';
        $decommissionArea = $this->areaService->decommissionArea($area_id, $reason );
        if (isset($decommissionArea)) {
            if ($decommissionArea['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionArea['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionArea['msg']);
            }
        }
    }
}
