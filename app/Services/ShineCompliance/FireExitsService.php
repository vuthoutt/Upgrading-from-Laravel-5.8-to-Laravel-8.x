<?php

namespace App\Services\ShineCompliance;

use App\Repositories\ShineCompliance\FireExitRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\LocationRepository;

class FireExitsService
{
    use CreateTempHazard;

    protected const HAZARD_TYPE = 67;
    protected const HAZARD_NAME = 'Inaccessible Fire Exits';

    private $areaRepository;
    private $locationRepository;
    private $fireExitRepository;
    private $hazardRepository;

    public function __construct(FireExitRepository $fireExitRepository,
                                AreaRepository $areaRepository,
                                LocationRepository $locationRepository,
                                HazardRepository $hazardRepository)
    {
        $this->fireExitRepository = $fireExitRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->hazardRepository = $hazardRepository;
    }

    public function updateOrCreateFireExit($data, $fire_exit_id = 0)
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

                $dataFireExit = [
                    'name' => \CommonHelpers::checkArrayKey($data, 'name'),
                    'type' => \CommonHelpers::checkArrayKey($data, 'type'),
                    'property_id' => $data['property_id'] ?? 0,
                    'assess_id' => $data['assess_id'] ?? 0,
                    'area_id' => $area_id,
                    'location_id' => $location_id,
                    'decommissioned' => 0,
                    'reason_na' => \CommonHelpers::checkArrayKey($data, 'reason_na'),
                    'accessibility' => (isset($data['accessibility'])) ? 1 : 0,
                    'comment' => \CommonHelpers::checkArrayKey($data, 'comment'),
                ];

                $fireExit = $this->fireExitRepository->updateOrCreate(['id' => $fire_exit_id], $dataFireExit);
                $message = 'Updated Fire Exit Successfully!';

                // create Hazard if inaccessibility
                if ($dataFireExit['accessibility'] == 0) {
                    if (empty($fireExit->hazard_id)) {
                        $hazard = $this->createTempHazard($fireExit, $fireExit->assessment->classification ?? 0);
                        $fireExit->hazard_id = $hazard;
                        $fireExit->save();
                    } else {
                        $this->hazardRepository->revertHazardAccessible($fireExit->hazard_id);
                    }
                } else {
                    $this->hazardRepository->removeHazardAccessible($fireExit->hazard_id);
                }

                if($fire_exit_id == 0){
                    $message = 'Added Fire Exit Successfully!';
                    $fireExit->reference =  'FE' . $fireExit->id;
                    $fireExit->record_id =  $fireExit->id;
                    $fireExit->save();
                }

                // Save Photo
                if (isset($data['photo'])) {
                    \CommonHelpers::saveFileShineDocumentStorage($data['photo'], $fireExit->id, FIRE_EXIT_PHOTO);
                }
            }
            \DB::commit();

            return $response = \CommonHelpers::successResponse($message, $fireExit ?? []);
        } catch (\Exception $exception) {
            dd($exception);
            \DB::rollBack();
            \Log::error($exception);

        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create Fire Exit. Please try again!');
    }


    public function getFireExitDetail($fire_exit_id)
    {
        return $this->fireExitRepository->where('id', $fire_exit_id)->first();
    }

    public function decommissionFireExit($fireExit,$reason_decommissioned)
    {
        try {
            if($fireExit->decommissioned == HAZARD_UNDECOMMISSION){
                $fireExit->decommissioned = HAZARD_DECOMMISSION;
                if ($reason_decommissioned) {
                    $fireExit->reason_decommissioned = $reason_decommissioned;
                }
                $fireExit->save();
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Fire Exit. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Fire Exit Successfully!', $fireExit);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Fire Exit. Please try again!');
    }

    public function recommissionFireExit($fireExit)
    {
        try {
            if($fireExit->decommissioned == HAZARD_DECOMMISSION){
                $fireExit->decommissioned = HAZARD_UNDECOMMISSION;
                $fireExit->save();
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Fire Exit. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Fire Exit Successfully!', $fireExit);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Fire Exit. Please try again!');
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
