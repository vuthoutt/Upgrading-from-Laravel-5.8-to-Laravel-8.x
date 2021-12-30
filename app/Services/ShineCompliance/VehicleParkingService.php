<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\VehicleParkingRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\LocationRepository;

class VehicleParkingService
{
    use CreateTempHazard;
    protected const HAZARD_TYPE = 68;
    protected const HAZARD_NAME = 'Inaccessible Vehicle Parking';

    private $vehicleParkingRepository;
    private $areaRepository;
    private $locationRepository;
    private $hazardRepository;

    public function __construct(VehicleParkingRepository $vehicleParkingRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                HazardRepository $hazardRepository)
    {
        $this->vehicleParkingRepository = $vehicleParkingRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->hazardRepository = $hazardRepository;
    }

    public function updateOrCreateVehicleParking($data, $vehicle_parking_id = 0)
    {
        try {
            \DB::beginTransaction();
            if (!is_null($data) and count($data) > 0) {
                //if area is new create area
                $area_id = $data['area_id'] ?? 0;
                $location_id = $data['location_id'] ?? 0;
                if ($area_id == HAZARD_NEW_AREA) {
                    $create_area_data = [
                        'property_id' => $data['property_id'] ?? 0,
                        'survey_id' => $data['assess_id'] ? HAZARD_SURVEY_ID_DEFAULT : 0,
                        'assess_id' => $data['assess_id'] ?? 0,
                        'area_reference' => $data['area_reference'] ?? '',
                        'description' => $data['area_description']  ?? '',
                        'state' => AREA_ACCESSIBLE_STATE,
                        'decommissioned' => 0,
                        'created_by' => \Auth::user()->id
                    ];
                    $area_id = $this->createArea($create_area_data);
                }

                if ($location_id == HAZARD_NEW_LOCATION) {
                    $create_data_location = [
                        'area_id'                      => $area_id,
                        'survey_id'                    => $data['assess_id'] ? HAZARD_SURVEY_ID_DEFAULT : 0,
                        'property_id'                  => $data['property_id'] ?? 0,
                        'is_locked'                    => 0,
                        'assess_id'                    => $data['assess_id'] ?? 0,
                        'state'                        => LOCATION_STATE_ACCESSIBLE,
                        'version'                      => 1,
                        'description'                  => $data['location_description'] ?? '',
                        'location_reference'           => $data['location_reference'] ?? '',
                        'created_by' => \Auth::user()->id
                    ];
                    $location_id = $this->createLocation($create_data_location);
                }

                $dataVehicleParking = [
                    'name' => \CommonHelpers::checkArrayKey($data, 'name'),
                    'property_id' => $data['property_id'] ?? 0,
                    'assess_id' => $data['assess_id'] ?? 0,
                    'area_id' => $area_id,
                    'location_id' => $location_id,
                    'decommissioned' => 0,
                    'reason_na' => \CommonHelpers::checkArrayKey($data, 'reason_na'),
                    'accessibility' => (isset($data['accessibility'])) ? 1 : 0,
                    'comment' => \CommonHelpers::checkArrayKey($data, 'comment'),
                ];

                $vehicleParking = $this->vehicleParkingRepository->updateOrCreate(['id' => $vehicle_parking_id], $dataVehicleParking);

                $message = 'Updated Vehicle Parking Successfully!';

                // create Hazard if inaccessibility
                if ($dataVehicleParking['accessibility'] == 0) {
                    if (empty($vehicleParking->hazard_id)) {
                        $hazard = $this->createTempHazard($vehicleParking, $vehicleParking->assessment->classification ?? 0);
                        $vehicleParking->hazard_id = $hazard;
                        $vehicleParking->save();
                    } else {
                        $this->hazardRepository->revertHazardAccessible($vehicleParking->hazard_id);
                    }
                } else {
                    $this->hazardRepository->removeHazardAccessible($vehicleParking->hazard_id);
                }

                if($vehicle_parking_id == 0){
                    $message = 'Added Vehicle Parking Successfully!';
                    $vehicleParking->reference =  'VP' . $vehicleParking->id;
                    $vehicleParking->record_id =  $vehicleParking->id;
                    $vehicleParking->save();
                }

                // Save Photo
                if (isset($data['photo'])) {
                    \CommonHelpers::saveFileShineDocumentStorage($data['photo'], $vehicleParking->id, VEHICLE_PARKING_PHOTO);
                }
            }
            \DB::commit();

            return $response = \CommonHelpers::successResponse($message, $vehicleParking ?? []);
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception);
            dd($exception);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create Vehicle Parking. Please try again!');
    }

    public function getVehicleParkingDetail($vehicle_parking_id)
    {
        return $this->vehicleParkingRepository->where('id', $vehicle_parking_id)->first();
    }

    public function decommissionVehicleParking($vehicleParking, $reason_decommissioned)
    {
        try {
            if($vehicleParking->decommissioned == HAZARD_UNDECOMMISSION){
                $vehicleParking->decommissioned = HAZARD_DECOMMISSION;
                if ($reason_decommissioned) {
                    $vehicleParking->reason_decommissioned = $reason_decommissioned;
                }
                $vehicleParking->save();
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Vehicle Parking. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Vehicle Parking Successfully!', $vehicleParking);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Vehicle Parking. Please try again!');
    }

    public function recommissionVehicleParking($vehicleParking)
    {
        try {
            if($vehicleParking->decommissioned == HAZARD_DECOMMISSION){
                $vehicleParking->decommissioned = HAZARD_UNDECOMMISSION;
                $vehicleParking->save();
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Vehicle Parking. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Vehicle Parking Successfully!', $vehicleParking);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Vehicle Parking. Please try again!');
    }

    public function getRegisterVehicleParkings($property_id, $limit = 1000, $request = null) {
        return $this->vehicleParkingRepository->getRegisterVehicleParkings($property_id, $limit,$request);
    }

    public function createArea($data_area) {
        $area_new = $this->areaRepository->create($data_area);
        $id = $area_new->id;
        $refArea = "AF" . $id;
        $area_new->reference = $refArea;
        $area_new->record_id =  $id;
        $area_new->save();
        return $id;
    }

    // create basic location data
    public function createLocation($data_location) {

        $location_new = $this->locationRepository->create($data_location);
        $id = $location_new->id;
        $refLocation = "RL" . $id;
        $location_new->reference = $refLocation;
        $location_new->record_id =  $id;
        $location_new->save();
        // update or create relation
        $this->locationRepository->updateOrCreateLocationInfo($id, []);
        $this->locationRepository->updateOrCreateLocationVoid($id, []);
        $this->locationRepository->updateOrCreateLocationConstruction($id, []);
        return $id;
    }
}
