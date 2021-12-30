<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\AssemblyPointRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\LocationRepository;

class AssemblyPointService
{
    use CreateTempHazard;

    protected const HAZARD_TYPE = 66;
    protected const HAZARD_NAME = 'Inaccessible Assembly Points';

    private $assemblyPointRepository;
    private $areaRepository;
    private $locationRepository;
    private $hazardRepository;

    public function __construct(AssemblyPointRepository $assemblyPointRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                HazardRepository $hazardRepository)
    {
        $this->assemblyPointRepository = $assemblyPointRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->hazardRepository = $hazardRepository;
    }

    public function updateOrCreateAssemblyPoint($data, $assembly_point_id = 0)
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

                $dataAssembly = [
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

                $assemblyPoint = $this->assemblyPointRepository->updateOrCreate(['id' => $assembly_point_id], $dataAssembly);
                $message  = 'Updated Assembly Point Successfully!';

                // create Hazard if inaccessibility
                if ($dataAssembly['accessibility'] == 0) {
                    if (empty($assemblyPoint->hazard_id)) {
                        $hazard = $this->createTempHazard($assemblyPoint, $assemblyPoint->assessment->classification ?? 0);
                        $assemblyPoint->hazard_id = $hazard;
                        $assemblyPoint->save();
                    } else {
                        $this->hazardRepository->revertHazardAccessible($assemblyPoint->hazard_id);
                    }

                } else {
                    $this->hazardRepository->removeHazardAccessible($assemblyPoint->hazard_id);
                }

                if($assembly_point_id == 0){
                    $message  = 'Added Assembly Point Successfully!';
                    $assemblyPoint->reference =  'AP' . $assemblyPoint->id;
                    $assemblyPoint->record_id =  $assemblyPoint->id;
                    $assemblyPoint->save();
                }

                // Save Photo
                if (isset($data['photo'])) {
                    \CommonHelpers::saveFileShineDocumentStorage($data['photo'], $assemblyPoint->id, ASSEMBLY_POINT_PHOTO);
                }
            }
            \DB::commit();

            return $response = \CommonHelpers::successResponse($message, $assemblyPoint ?? []);
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error($exception);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create Assembly Point. Please try again!');
    }

    public function getAssemblyDetail($assembly_point_id)
    {
        return $this->assemblyPointRepository->where('id', $assembly_point_id)->first();
    }


    public function decommissionAssembly($assemblyPoint,$reason_decommissioned)
    {
        try {
            if($assemblyPoint->decommissioned == HAZARD_UNDECOMMISSION){
                $assemblyPoint->decommissioned = HAZARD_DECOMMISSION;
                if ($reason_decommissioned) {
                    $assemblyPoint->reason_decommissioned = $reason_decommissioned;
                }
                $assemblyPoint->save();
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Assembly Point. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Assembly Point Successfully!', $assemblyPoint);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Assembly Point. Please try again!');
    }

    public function recommissionAssembly($assemblyPoint)
    {
        try {
            if($assemblyPoint->decommissioned == HAZARD_DECOMMISSION){
                $assemblyPoint->decommissioned = HAZARD_UNDECOMMISSION;
                $assemblyPoint->save();
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Assembly Point. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Assembly Point Successfully!', $assemblyPoint);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Assembly Point. Please try again!');
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
