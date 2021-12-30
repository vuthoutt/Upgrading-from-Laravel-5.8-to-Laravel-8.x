<?php

namespace App\Http\Controllers\ShineCompliance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Request\ShineCompliance\Location\LocationRequest;
use App\Services\ShineCompliance\LocationService;
use App\Services\ShineCompliance\AreaService;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $locationService;
    private $areaService;

    public function __construct(LocationService $locationService, AreaService $areaService)
    {
        $this->locationService = $locationService;
        $this->areaService = $areaService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index($area_id, Request $request)
    {
        $area = $area =  $this->areaService->findArea($area_id);
        $locations = $this->locationService->getLocationInArea($area_id, $request, 9);
        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } else if (\CommonHelpers::isClientUser()) {
                if (($area->property->client_id ?? 0)!= \Auth::user()->client_id) {
                    $can_add_new = false;
                }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ROOM_LOCATION) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $area->property_id)) {
                $can_add_new = false;
            }
        }
        //todo search ajax
        if($request->ajax()){
            view('shineCompliance.properties.room.index',['locations' => $locations, 'area_id' => $area_id, 'area' => $area, 'can_add_new' => $can_add_new])->render();
        }

        // audit
        $comment = \Auth::user()->full_name . " viewed location list page " . $area->reference;
        \ComplianceHelpers::logAudit(LOCATION_TYPE, $area->id, AUDIT_ACTION_VIEW, $area->reference, $area->id, $comment);

        return view('shineCompliance.properties.room.index',['locations' => $locations, 'area_id' => $area_id, 'area' => $area, 'can_add_new' => $can_add_new]);
    }

    public function detail($id){
        $location = $this->locationService->getLocation($id);
        $can_update  = true;

        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
        } else if (\CommonHelpers::isClientUser()) {
                if ($property->client_id != \Auth::user()->client_id) {
                    $can_update = false;
                }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ROOM_LOCATION) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $location->property_id)) {
                $can_update = false;
            }
        }
        // audit
        $comment = \Auth::user()->full_name . " viewed location detail page " . $location->reference;
        \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_VIEW, $location->reference, $location->id, $comment);
        return view('shineCompliance.properties.room.detail',['location' => $location,'can_update' => $can_update]);
    }

    public function getAddLocation($area_id) {
        $area =  $this->areaService->findArea($area_id);
        $locationVoids = $this->locationService->getLocationDropdown(LOCATION_VOID_ID);
        $locationContructions = $this->locationService->getLocationDropdown(LOCATION_CONTRUCTION_DETAILS_ID);
        $reasons = $this->locationService->getDropdownById(LOCATION_REASONS);

        $locationVoidsData = $this->locationService->getLocationVoidsData($locationVoids);
        $locationContructionsDatas = $this->locationService->getLocationContructionsData($locationContructions);
        // audit
        $comment = \Auth::user()->full_name . " viewed location add page on area" . $area->reference;
        \ComplianceHelpers::logAudit(LOCATION_TYPE, $area->id, AUDIT_ACTION_VIEW, $area->reference, $area->id, $comment);

        return view('shineCompliance.properties.room.add_location', ['reasons' => $reasons,
            'locationVoidsData' => $locationVoidsData,
            'area' => $area,
            'locationContructionsDatas' => $locationContructionsDatas
             ]);
    }

    public function postAddLocation($area_id, LocationRequest $request) {
        $data = $request->validated();
        $location = $this->locationService->updateOrCreateLocation($area_id, $data);
        if (isset($location)) {
            if ($location['status_code'] == STATUS_OK) {
                if ($location['data']->survey_id == 0) {
                    return redirect()->route('shineCompliance.location.detail',['id' => $location['data']])->with('msg', $location['msg']);
                    //for survey
                } else {
                    return redirect()->route('property.surveys',['survey_id' => $location['data']->survey_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $location['data']->id] )->with('msg', $location['msg']);
                }
            } else {
                return redirect()->back()->with('err', $location['msg']);
            }
        }
    }

    public function getEditLocation($id) {
        $location = $this->locationService->getLocation($id);
        $locationVoids = $this->locationService->getLocationDropdown(LOCATION_VOID_ID);
        $locationContructions = $this->locationService->getLocationDropdown(LOCATION_CONTRUCTION_DETAILS_ID);
        $reasons = $this->locationService->getDropdownById(LOCATION_REASONS);
        // $area = $this->areaRepository->getArea($request->area_id);
        // if (empty($area)) {
        //     abort(404);
        // }

        $locationVoidsData = $this->locationService->getLocationSelectedVoidsData($locationVoids, $location);
        $locationContructionsDatas = $this->locationService->getLocationSelectedContructionsData($locationContructions, $location);

        // audit
        $comment = \Auth::user()->full_name . " viewed location edit page " . $location->reference;
        \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_VIEW, $location->reference, $location->id, $comment);

        return view('shineCompliance.properties.room.edit_location', [
                'location' => $location,
                'reasons' => $reasons,
                'locationVoidsData' => $locationVoidsData,
                'locationContructionsDatas' => $locationContructionsDatas
             ]);
    }

    public function postEditLocation($id, LocationRequest $request) {
        $data = $request->validated();

        $location = $this->locationService->updateOrCreateLocation(null, $data, $id);
        if (isset($location)) {
            if ($location['status_code'] == STATUS_OK) {
                if ($location['data']->survey_id == 0) {
                    return redirect()->route('shineCompliance.location.detail',['id' => $location['data']])->with('msg', $location['msg']);
                    //for survey
                } else {
                    return redirect()->route('property.surveys',['survey_id' => $location['data']->survey_id ,
                        'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $id,
                        'position' => $request->position,
                        'category' => $request->category,
                        'pagination_type' => $request->pagination_type,
                    ] )->with('msg', $location['msg']);
                }
            } else {
                return redirect()->back()->with('err', $location['msg']);
            }
        }
    }

    public function decommissionLocation($location_id,Request $request) {
        $reason = $request->location_decommisson_reason_add ?? '';
        $decommissionLocation = $this->locationService->decommissionLocation($location_id,$reason);
        if (isset($decommissionLocation)) {
            if ($decommissionLocation['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionLocation['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionLocation['msg']);
            }
        }
    }

    public function locationReason(Request $request) {
        $reasons = $this->locationService->getDropdownById(LOCATION_REASONS, $request->parent_id ?? 0);
        return response()->json(['status_code' => 200, 'data' => $reasons]);
    }
}
