<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\VehicleParkingService;
use App\Services\ShineCompliance\PropertyService;
use App\Http\Request\ShineCompliance\Assessment\VehicleParkingRequest;
use Illuminate\Http\Request;

class VehicleParkingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $vehicleParkingService;
    private $assessmentService;
    private $propertyService;

    public function __construct(
        AssessmentService $assessmentService,
        PropertyService $propertyService,
        VehicleParkingService $vehicleParkingService
    )
    {
        $this->assessmentService = $assessmentService;
        $this->propertyService = $propertyService;
        $this->vehicleParkingService = $vehicleParkingService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function getAddVehicleParking($property_id, Request $request)
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
        // TODO
        return view('shineCompliance.vehicleParking.get_add_vehicle_parking', compact('assessment','property',
            'areas',
            'property_id',
            'assess_id',
            'reasons'));
    }

    public function postAddVehicleParking(VehicleParkingRequest $request)
    {
        $result = $this->vehicleParkingService->updateOrCreateVehicleParking($request->validated());
        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                //todo redirect to detail
                return redirect()->route('shineCompliance.assessment.get_vehicle_parking',['id' => $result['data']->id ?? 0])->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function getVehicleParking($vehicle_parking_id)
    {
        if(!$vehicleParking = $this->vehicleParkingService->getVehicleParkingDetail($vehicle_parking_id)){
            abort(404);
        }

        $can_update  = true;
        if (!\CommonHelpers::isSystemClient() and ($vehicleParking->assess_id == 0)) {
            $can_update  = false;
        } elseif(\CommonHelpers::isSystemClient() and ($vehicleParking->assess_id == 0)) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_VEHICLE_PARKING) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $vehicleParking->property_id)) {
                $can_update = false;
            }
        }

        // log audit
        $comment = \Auth::user()->full_name . " viewed vehicle parking detail";
        \ComplianceHelpers::logAudit(VEHICLE_PARKING_TYPE, $vehicleParking->id, AUDIT_ACTION_VIEW, $vehicleParking->reference, $vehicleParking->property_id, $comment);

        return view('shineCompliance.vehicleParking.get_vehicle_parking', compact('vehicleParking','can_update'));
    }

    public function getEditVehicleParking($vehicle_parking_id)
    {
        if(!$vehicleParking = $this->vehicleParkingService->getVehicleParkingDetail($vehicle_parking_id)){
            abort(404);
        }
        $areas = $this->assessmentService->getAreaAssessment($vehicleParking->assess_id,  $vehicleParking->property_id);
        $locations = $this->assessmentService->getLocationsByAssessmentAndArea($vehicleParking->assess_id, $vehicleParking->area_id);
        $reasons = $this->assessmentService->getEquipmentDropdownData(EQUIPMENT_REASON_INACCESS);
        return view('shineCompliance.vehicleParking.get_edit_vehicle_parking', compact('vehicleParking',
            'areas',
            'locations',
            'reasons'));
    }

    public function postEditVehicleParking($vehicle_parking_id, VehicleParkingRequest $request)
    {
        if(!$vehicleParking = $this->vehicleParkingService->getVehicleParkingDetail($vehicle_parking_id)){
            abort(404);
        }

        $result = $this->vehicleParkingService->updateOrCreateVehicleParking($request->validated(), $vehicle_parking_id);
        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                //todo redirect to detail
                return redirect()->route('shineCompliance.assessment.get_vehicle_parking',['id' => $vehicle_parking_id])->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postDecommissionVehicleParking($vehicle_parking_id, Request $request)
    {
        if(!$vehicleParking = $this->vehicleParkingService->getVehicleParkingDetail($vehicle_parking_id)){
            abort(404);
        }
        $reason_decommissioned = $request->get('decommission_reason');
        $result = $this->vehicleParkingService->decommissionVehicleParking($vehicleParking, $reason_decommissioned);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postRecommissionVehicleParking($vehicle_parking_id)
    {
        if(!$vehicleParking = $this->vehicleParkingService->getVehicleParkingDetail($vehicle_parking_id)){
            abort(404);
        }
        $result = $this->vehicleParkingService->recommissionVehicleParking($vehicleParking);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function listRegisterVehicleParking($property_id, Request $request) {
        $parkings = $this->vehicleParkingService->getRegisterVehicleParkings($property_id, 9, $request);
        $property =  $this->propertyService->getProperty($property_id);

        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
        } elseif(\CommonHelpers::isSystemClient()) {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_VEHICLE_PARKING) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

        // log audit
        $comment = \Auth::user()->full_name . " viewed register vehicle parking list for property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(VEHICLE_PARKING_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);

        if($request->ajax()) {
            return view('shineCompliance.properties.parking', compact('property_id', 'property', 'can_add_new', 'parkings'))->render();
        }
        return view('shineCompliance.properties.parking', [
            'property_id' => $property_id,
            'property' => $property,
            'can_add_new' => $can_add_new,
            'parkings' => $parkings
        ]);
    }

}
