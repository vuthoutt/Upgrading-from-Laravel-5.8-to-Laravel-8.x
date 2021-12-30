<?php

namespace App\Services\ShineCompliance;

use App\Models\ShineCompliance\HazardActionResponsibility;
use App\Models\ShineCompliance\HazardInAccessibleReason;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Models\ShineCompliance\HazardActionRecommendationNoun;
use App\Models\ShineCompliance\HazardActionRecommendationVerb;
use App\Models\ShineCompliance\HazardLikelihoodHarm;
use App\Models\ShineCompliance\HazardPotential;
use App\Models\ShineCompliance\HazardSpecificLocation;
use App\Models\ShineCompliance\HazardSpecificLocationValue;
use App\Models\ShineCompliance\HazardType;
use App\Models\ShineCompliance\HazardMeasurement;

class HazardService{

    private $areaRepository;
    private $locationRepository;
    private $hazardRepository;

    public function __construct(HazardRepository $hazardRepository, AreaRepository $areaRepository, LocationRepository $locationRepository){
        $this->hazardRepository = $hazardRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
    }

    public function getHazardDetail($hazard_id, $relations = []){
        return $this->hazardRepository->getHazardDetail($hazard_id, $relations);
    }

    public function getHazardType($assessment_type = null){
        if ($assessment_type) {
            // temporary use for now
            // if ($assessment_type == ASSESSMENT_HS_TYPE) {
            //     $assessment_type = ASSESSMENT_WATER_TYPE;
            // }
            return HazardType::where('type', $assessment_type)->get();
        } else {
            return HazardType::where('parent_id', 0)->get();
        }
    }

    public function getHazardMeasurement()
    {
        return HazardMeasurement::all();
    }

    public function getHazardPotential(){
        return HazardPotential::all();
    }

    public function getHazardActionResponsibilities()
    {
        return HazardActionResponsibility::all();
    }

    public function getHazardLikelihoodHarm(){
        return HazardLikelihoodHarm::all();
    }

    public function getHazardActionRecommendationNoun($assessment_type = null){
        if ($assessment_type) {
            // temporary use for now
            // if ($assessment_type == ASSESSMENT_HS_TYPE) {
            //     $assessment_type = ASSESSMENT_WATER_TYPE;
            // }
            return HazardActionRecommendationNoun::where('type', $assessment_type)->orderBy('description')->orderBy('order')->get();
        } else {
            return HazardActionRecommendationNoun::orderBy('description')->orderBy('order')->get();
        }
    }

    public function getHazardActionRecommendationVerb($assessment_type = null){
        if ($assessment_type) {
            // temporary use for now
            if ($assessment_type == ASSESSMENT_HS_TYPE) {
                $assessment_type = ASSESSMENT_WATER_TYPE;
            }
            return HazardActionRecommendationVerb::where('type', $assessment_type)->orderBy('description')->get();
        } else {
            return HazardActionRecommendationVerb::orderBy('description')->get();
        }
    }

    public function getAllHazardSpecificLocation($assessment_type = null)
    {
        if ($assessment_type) {
            return HazardSpecificLocation::where('assess_type', $assessment_type)->get();
        } else {
            return HazardSpecificLocation::all();
        }
    }

    public function updateOrCreateHazard($data, $hazard_id = 0){
        try{
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

                $data_hazard = [
                    'name' => \CommonHelpers::checkArrayKey($data,'name'),
                    'property_id' => $data['property_id'] ?? 0,
                    'assess_id' => $data['assess_id'] ?? 0,
                    'assess_type' => $data['assess_type'] ?? 0,
                    'created_date' => $data['created_date'] ?? '',
                    'area_id' => $area_id,
                    'location_id' => $location_id,
                    'linked_question_id' => \CommonHelpers::checkArrayKey($data,'linked_question_id'),
                    'decommissioned' => 0,
                    'type' => \CommonHelpers::checkArrayKey($data,'type'),
                    'reason' => \CommonHelpers::checkArrayKey($data,'type') == HAZARD_TYPE_INACCESSIBLE_ROOM ? \CommonHelpers::checkArrayKey($data,'reason') : 0,
                    'reason_other' => \CommonHelpers::checkArrayKey($data,'reason_other') ?? 0,
                    'likelihood_of_harm' => \CommonHelpers::checkArrayKey($data,'likelihood_of_harm'),
                    'hazard_potential' => \CommonHelpers::checkArrayKey($data,'hazard_potential'),
                    'total_risk' => \CommonHelpers::checkArrayKey($data,'total-score'),
                    'extent' => \CommonHelpers::checkArrayKey($data,'extent'),
                    'photo_override' =>  $data['photo_override'] ?? 0,
                    'measure_id' => \CommonHelpers::checkArrayKey($data,'measure_id'),
                    'act_recommendation_noun' => \CommonHelpers::checkArrayKey($data,'act_recommendation_noun'),
                    'act_recommendation_noun_other' => \CommonHelpers::checkArrayKey($data,'act_recommendation_noun_other'),
                    'act_recommendation_verb' => \CommonHelpers::checkArrayKey($data,'act_recommendation_verb'),
                    'act_recommendation_verb_other' => \CommonHelpers::checkArrayKey($data,'act_recommendation_verb_other'),
                    'action_responsibility' => \CommonHelpers::checkArrayKey($data,'action_responsibility'),
                    'comment' => \CommonHelpers::checkArrayKey($data,'comment'),
                ];
                if(!$hazard_id){
                    $data_hazard['created_by'] = \Auth::user()->id;
                } else {
                    $data_hazard['updated_by'] = \Auth::user()->id;
                }
                $hazard = $this->hazardRepository->updateOrCreate(['id' => $hazard_id], $data_hazard);
                if(!$hazard_id){
                    if ($hazard->assess_type == ASSESSMENT_FIRE_TYPE) {
                        $hazard->reference = 'FH'.$hazard->id;
                    } elseif ($hazard->assess_type == ASSESSMENT_WATER_TYPE) {
                        $hazard->reference = 'WH'.$hazard->id;
                    } elseif ($hazard->assess_type == ASSESSMENT_HS_TYPE) {
                        $hazard->reference = 'HSH'.$hazard->id;
                    } else {
                        $hazard->reference = 'HZ'.$hazard->id;
                    }

                    $hazard->record_id = $hazard->id;
                    $hazard->save();
                }
                if($hazard){
                    if (isset($data['specificLocations-other']) and $data['specificLocations-other'] != '') {
                        $this->insertDropdownValue($hazard->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'),\CommonHelpers::checkArrayKey($data,'specificLocations-other'));
                    } else {
                        if (is_null(\CommonHelpers::checkArrayKey($data,'specificLocations3'))) {
                            $this->insertDropdownValue($hazard->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'));
                        } else {
                            $this->insertDropdownValue($hazard->id, SPECIFIC_LOCATION_ID, 0,\CommonHelpers::checkArrayKey($data,'specificLocations3'));
                        }
                    }
                }
                if($hazard->photo_override == 0){
                    //create item image
                    if (isset($data['hazard_photo'])) {
                        $save_hazard_photo = \CommonHelpers::saveFileShineDocumentStorage($data['hazard_photo'], $hazard->id, HAZARD_PHOTO);
                    }
                    if (isset($data['location_photo'])) {
                        $save_location_photo = \CommonHelpers::saveFileShineDocumentStorage($data['location_photo'], $hazard->id, HAZARD_LOCATION_PHOTO);
                    }
                    if (isset($data['additional_photo'])) {
                        $save_additional_photo = \CommonHelpers::saveFileShineDocumentStorage($data['additional_photo'], $hazard->id, HAZARD_ADDITION_PHOTO);
                    }
                }
            }
            \DB::commit();
            if ($hazard_id > 0) {

                //log audit
                \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_EDIT, $hazard->reference, $hazard->property_id);
                return $response = \CommonHelpers::successResponse('Updated Hazard Successfully!', $hazard);
            } else {
                //log audit
                \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_ADD, $hazard->reference, $hazard->property_id);
                return $response = \CommonHelpers::successResponse('Added Hazard Successfully!', $hazard);
            }
        } catch (\Exception $e){
            \DB::rollback();
            \Log::error($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to adding Hazard! Please try again');
        }
    }

    public function getSpecificlocationValue($hazard_id) {
        $dataValue = HazardSpecificLocationValue::where('hazard_id', $hazard_id)->first();
        if (!is_null($dataValue)) {
            $dataValue_ids = explode(",",$dataValue->value);
            $allSpecifics = HazardSpecificLocation::whereIn('id', $dataValue_ids);

            $dataValue_ids = explode(",",$dataValue->value);

            if (is_null($allSpecifics->first())) {
                $specificParentIds = [];
            } else {
                $specificParentIds = $this->getallParentsIds($allSpecifics->first()->allParents ?? null);
            }
            // if does not exist parent : other selected
            if (empty($specificParentIds)) {
                return $dataValue_ids;
            }
            array_push($specificParentIds, $dataValue_ids);
            return $specificParentIds;
        }
        return [];
    }

    public function getHazardSpecificLocation($assessment_type = null, $parent_id = 0) {
        if ($assessment_type) {
            return HazardSpecificLocation::with('allChildrens')->where('parent_id', $parent_id)->where('assess_type', $assessment_type)->get();
        } else {
            return HazardSpecificLocation::with('allChildrens')->where('parent_id', $parent_id)->get();
        }
    }

    public function getHazardExtent($parent_id = 0) {
        return HazardMeasurement::where('parent_id', $parent_id)->where('decommissioned',0)->get();
    }

    public function getallParentsIds($data) {
        $id = [];
        if (!is_null($data)) {
            array_unshift($id, $data->id);
            if (!is_null($data->allParents)) {
                $parent1 = $data->allParents;
                array_unshift($id, $data->allParents->id);
                if (!is_null($parent1->allParents)) {
                    $parent2 = $parent1->allParents;
                    array_unshift($id, $parent1->allParents->id);
                    if (!is_null($parent2->allParents)) {
                        $parent3 = $parent2->allParents;
                        array_unshift($id, $parent2->allParents->id);
                    }
                }
            }
        }
        return $id;
    }
    public function confirmHazard($hazard){
        try {

            $hazard->is_temp = 0;
            $hazard->save();

            return $response = \CommonHelpers::successResponse('Confirmed Hazard Successfully!', $hazard);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Hazard. Please try again!');
    }

    public function decommissionHazard($hazard, $reason, $linked_project){
        try {
            if($hazard->decommissioned == HAZARD_UNDECOMMISSION){
                $hazard->decommissioned = HAZARD_DECOMMISSION;
                $hazard->decommissioned_reason = $reason;
                $hazard->linked_project_id = $linked_project;
                $hazard->save();

                // log audit
                \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_DECOMMISSION, $hazard->reference, $hazard->property_id);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Hazard. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Hazard Successfully!', $hazard);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Hazard. Please try again!');
    }

    public function recommissionHazard($hazard){
        try {
            if($hazard->decommissioned == HAZARD_DECOMMISSION){
                $hazard->decommissioned = HAZARD_UNDECOMMISSION;
                $hazard->save();

                // log audit
                \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_RECOMMISSION, $hazard->reference, $hazard->property_id);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Hazard. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Hazard Successfully!', $hazard);
        } catch (\Exception $e){
            \Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Hazard. Please try again!');
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

    public function insertDropdownValue($item_id,$dropdown_item_id,$parent_id, $value, $other = null)
    {
        if (is_array($value)) {
            if ($dropdown_item_id == SPECIFIC_LOCATION_ID) {
                $value = implode(",", $value);
            } else {
                $value = end($value);
            }
        }
        if (is_array($other)) {
            $other = implode(",", $other);
        }

        $dataDropdownValue = [
            'parent_id' => $parent_id,
            'value' => $value,
            'other' => $other
        ];

        switch ($dropdown_item_id) {
            case SPECIFIC_LOCATION_ID:
                $data = HazardSpecificLocationValue::updateOrCreate(['hazard_id' => $item_id], $dataDropdownValue);
                break;
        }
    }

    public function getInaccessibleReasonHazard(){
        return HazardInAccessibleReason::orderBy('other','asc')->orderBy('description')->get();
    }

    public function listNaHazards($property_id, $assess_type, $relations = []){
        return $this->hazardRepository->listNaHazards($property_id, $assess_type, $relations);
    }
}
