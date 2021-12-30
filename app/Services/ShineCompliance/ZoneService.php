<?php

namespace App\Services\ShineCompliance;

use App\Helpers\CommonHelpers;
use App\Helpers\ComplianceHelpers;
use App\Models\Location;
use App\Models\DecommissionReason;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Models\ShineCompliance\Hazard;
use App\Models\ShineCompliance\Item;
use App\Repositories\ShineCompliance\ZoneRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\SurveyRepository ;

class ZoneService{

    private $zoneRepository;
    private $itemRepository;
    private $locationRepository;
    private $hazardRepository;
    private $surveyRepository;
    private $areaRepository;

    public function __construct(ZoneRepository $zoneRepository,
                                ItemRepository $itemRepository,
                                LocationRepository $locationRepository,
                                PropertyRepository $propertyRepository,
                                SurveyRepository $surveyRepository,
                                AreaRepository $areaRepository,
                                HazardRepository $hazardRepository){
        $this->zoneRepository = $zoneRepository;
        $this->itemRepository = $itemRepository;
        $this->propertyRepository = $propertyRepository;
        $this->surveyRepository = $surveyRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->hazardRepository = $hazardRepository;

    }

    public function getZone($id, $relation = []){
        return $this->zoneRepository->getZone($id, $relation);
    }

    public function getAllZone($id, $limit, $query, $client_id = 1){
        return $this->zoneRepository->getAllZone($id, $limit, $query, $client_id);
    }

    public function updateZone($data){
        $zone_id = $data->zone_id ?? "";

        $zone = $this->zoneRepository->getZone($zone_id, []);

        if (!is_null($zone_id)) {
            try {
                \DB::beginTransaction();
                $zoneUpdate = $zone->update(['zone_name' => $data['zone_name']]);
                //log audit
               $comment = \Auth::user()->full_name . " edited group " . $zone->zone_name;
               \CommonHelpers::logAudit(PROPERTY_GROUP_TYPE, $zone->id, AUDIT_ACTION_EDIT, $zone->reference, $zone->client_id, $comment);

                if (isset($data['zone_image'])) {
                    $saveZoneImage = \CommonHelpers::saveFileShineDocumentStorage($data['zone_image'], $zone_id, ZONE_PHOTO);
                }
                \DB::commit();
                return $response = \ComplianceHelpers::successResponse('Property Group Updated Successfully!', $zone_id);
            } catch (\Exception $e) {
                \Log::debug($e);
                \DB::rollBack();
                return $response = \ComplianceHelpers::failResponse(STATUS_FAIL,'Can not update zone. Please try again!');
            }
        } else {
            return $response = \ComplianceHelpers::failResponse(STATUS_FAIL,'Can not find zone. Please try again!');
        }
    }

    public function createZone($data)
    {
        try {
            \DB::beginTransaction();
            $dataCreate = [
                'zone_name' => $data->zone_name,
                'zone_image' => $data->zone_image,
                'client_id' => $data->client_id,
                'parent_id' => 0,
            ];

            $zone = $this->zoneRepository->createZone($dataCreate);
            if ($zone) {
                $refZone = "PG" . $zone->id;
                $zone->where('id', $zone->id)->update(['reference' => $refZone]);

                //update EMP
                \CompliancePrivilege::setViewEMP(JR_GROUP_EMP, $zone->id, $zone->client_id);
                \CompliancePrivilege::setUpdateEMP(JR_UPDATE_GROUP_EMP, $zone->id, $zone->client_id);

                //log audit
               $comment = \Auth::user()->full_name . " added group " . $zone->zone_name;
               \CommonHelpers::logAudit(PROPERTY_GROUP_TYPE, $zone->id, AUDIT_ACTION_ADD, $refZone, $zone->client_id, $comment);
                if (isset($data['zone_image'])) {
                    $saveZoneImage = \CommonHelpers::saveFileShineDocumentStorage($data['zone_image'], $zone->id, ZONE_PHOTO);
                }
            }

            \DB::commit();
            return $response = \ComplianceHelpers::successResponse('Property Group Added Successfully!', $zone->id);
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return $response = \ComplianceHelpers::failResponse(STATUS_FAIL, 'Failed to create zone. Please try again!');
        }
    }

    public function listGroupByClient($client_id, $parent_id){
        return $this->zoneRepository->listGroupByClient($client_id, $parent_id);
    }

    public function findWhereiD($client_id, $parent_id){
        return $this->zoneRepository->listGroupByClient($client_id, $parent_id);
    }

    public function getAllProGroupDecommissionReason(){
        return DecommissionReason::where('type', 'group')->where('parent_id', 1)->get();
    }

    public function decommissionZone($id, $reason) {
        $zone = $this->zoneRepository->getZone($id, ['property','property.surveys','property.areas','property.locations','property.items',
            'property.assessment','property.fireExist','property.assemblyPoint','property.vehicleParking','property.system','property.equipment','property.programme','property.hazard']);
        try {
            \DB::beginTransaction();
            if ($zone->decommissioned == ZONE_DECOMMISSION) {
                $zone->decommissioned = ZONE_UNDECOMMISSION;
                $zone->last_revision = time();
                $zone->save();
                $zone->property()->update(['decommissioned' => PROPERTY_UNDECOMMISSION]);
                $comment = \Auth::user()->full_name . " recommissioned Property Group " . $zone->zone_name;
                \ComplianceHelpers::logAudit(PROPERTY_GROUP_TYPE, $zone->id, ZONE_UNDECOMMISSION, $zone->reference, $zone->client_id, $comment, 0 , $zone->id);
                if(isset($zone->property) && count($zone->property)) {
                    foreach ($zone->property as $property) {
                        $comment = \Auth::user()->full_name . " recommission Property " . $property->name;
                        \ComplianceHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_RECOMMISSION, $property->reference, $property->client_id, $comment, 0 , $property->id);
                        $property->surveys()->update(['decommissioned' => SURVEY_UNDECOMMISSION]);
                        $property->areas()->update(['decommissioned' => AREA_UNDECOMMISSION]);
                        $property->locations()->update(['decommissioned' => LOCATION_UNDECOMMISSION]);
                        $property->items()->update(['decommissioned' => ITEM_UNDECOMMISSION]);
                        $property->assessment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->fireExist()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->assemblyPoint()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->vehicleParking()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->system()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->equipment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->programme()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        $property->hazard()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED]);
                        if(isset($property->surveys) && count($property->surveys)) {
                            foreach ($property->surveys as $survey) {
                                $comment = \Auth::user()->full_name . " Recommission Survey ".$survey->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_RECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                            }
                        }
                        if(isset($property->areas) && count($property->areas)) {
                            foreach ($property->areas as $area) {
                                $comment = \Auth::user()->full_name . " Recommission Area ".$area->area_reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_RECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                            }
                        }

                        if(isset($property->locations) && count($property->locations)) {
                            foreach ($property->locations as $location) {
                                $comment = \Auth::user()->full_name . " Recommission Location ".$location->location_reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_RECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                            }
                        }

                        if(isset($property->items) && count($property->items)) {
                            foreach ($property->items as $item) {
                                $comment = \Auth::user()->full_name . " Recommission Item ".$item->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_RECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                            }
                        }

                        if(isset($property->assessment) && count($property->assessment)) {
                            foreach ($property->assessment as $assessment) {
                                $comment = \Auth::user()->full_name . " Recommission Assessment ".$assessment->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_RECOMMISSION, $assessment->reference, $assessment->property_id ,$comment, 0 ,$assessment->property_id);
                            }
                        }

                        if(isset($property->fireExist) && count($property->fireExist)) {
                            foreach ($property->fireExist as $fireExist) {
                                $comment = \Auth::user()->full_name . " Recommission Fire Exist ".$fireExist->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(FIRE_EXIT_TYPE, $fireExist->id, AUDIT_ACTION_RECOMMISSION, $fireExist->reference, $fireExist->assess_id ,$comment, 0 ,$fireExist->property_id);
                            }
                        }

                        if(isset($property->assemblyPoint) && count($property->assemblyPoint)) {
                            foreach ($property->assemblyPoint as $assemblyPoint) {
                                $comment = \Auth::user()->full_name . " Recommission Assembly Point Exist ".$assemblyPoint->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(ASSEMBLY_POINT, $assemblyPoint->id, AUDIT_ACTION_RECOMMISSION, $assemblyPoint->reference, $assemblyPoint->assess_id ,$comment, 0 ,$assemblyPoint->property_id);
                            }
                        }

                        if(isset($property->vehicleParking) && count($property->vehicleParking)) {
                            foreach ($property->vehicleParking as $vehicleParking) {
                                $comment = \Auth::user()->full_name . " Recommission Vehicle Parking ".$vehicleParking->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(VEHICLE_PARKING_TYPE, $vehicleParking->id, AUDIT_ACTION_RECOMMISSION, $vehicleParking->reference, $vehicleParking->assess_id ,$comment, 0 ,$vehicleParking->property_id);
                            }
                        }

                        if(isset($property->system) && count($property->system)) {
                            foreach ($property->system as $system) {
                                $comment = \Auth::user()->full_name . " Recommission System ".$system->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_RECOMMISSION, $system->reference, $system->assess_id ,$comment, 0 ,$system->property_id);
                            }
                        }

                        if(isset($property->equipment) && count($property->equipment)) {
                            foreach ($property->equipment as $equipment) {
                                $comment = \Auth::user()->full_name . " Recommission Equipment ".$equipment->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_RECOMMISSION, $equipment->reference, $equipment->assess_id ,$comment, 0 ,$equipment->property_id);
                            }
                        }

                        if(isset($property->programme) && count($property->programme)) {
                            foreach ($property->programme as $programme) {
                                $comment = \Auth::user()->full_name . " Recommission Programme ".$programme->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_RECOMMISSION, $programme->reference, $programme->assess_id ,$comment, 0 ,$programme->property_id);
                            }
                        }

                        if(isset($property->hazard) && count($property->hazard)) {
                            foreach ($property->hazard as $hazard) {
                                $comment = \Auth::user()->full_name . " Recommission Hazard ".$hazard->reference." by Recommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_RECOMMISSION, $hazard->reference, $hazard->assess_id ,$comment, 0 ,$hazard->property_id);
                            }
                        }
                        //todo decommission Assessment/Fire exit/Assembly Point/Vehicle parking/System/Programme/Equipment/Hazard
                    }
                }

                $response = \CommonHelpers::successResponse('Property Group Recommissioned Successfully!');
            } else {
                $zone->decommissioned = ZONE_DECOMMISSION;
                $zone->last_revision = time();
                $zone->save();
                $comment = \Auth::user()->full_name . " decommissioned Property Group " . $zone->zone_name;
                \ComplianceHelpers::logAudit(PROPERTY_GROUP_TYPE, $zone->id, ZONE_DECOMMISSION, $zone->reference, $zone->client_id, $comment, 0 , $zone->id);
                $zone->property()->update(['decommissioned' => PROPERTY_DECOMMISSION]);

                if(isset($zone->property) && count($zone->property)) {
                    foreach ($zone->property as $property) {
                        $comment = \Auth::user()->full_name . " decommissioned Property " . $property->name;
                        \ComplianceHelpers::logAudit(PROPERTY_TYPE, $property->id, AUDIT_ACTION_DECOMMISSION, $property->reference, $property->client_id, $comment, 0 , $property->id);
                        $property->surveys()->update(['decommissioned' => SURVEY_DECOMMISSION]);
                        $property->areas()->update(['decommissioned' => AREA_DECOMMISSION]);
                        $property->locations()->update(['decommissioned' => LOCATION_DECOMMISSION]);
                        $property->items()->update(['decommissioned' => ITEM_DECOMMISSION]);
                        $property->assessment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->fireExist()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->assemblyPoint()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->vehicleParking()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->system()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->equipment()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->programme()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        $property->hazard()->update(['decommissioned' => COMPLIANCE_ASSESSMENT_DECOMMISSIONED]);
                        if(isset($property->surveys) && count($property->surveys)) {
                            foreach ($property->surveys as $survey) {
                                $comment = \Auth::user()->full_name . " Decommissioned Survey ".$survey->reference." by Decommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(SURVEY_TYPE, $survey->id, AUDIT_ACTION_DECOMMISSION, $survey->reference, $survey->property_id ,$comment, 0 ,$survey->property_id);
                            }
                        }
                        if(isset($property->areas) && count($property->areas)) {
                            foreach ($property->areas as $area) {
                                $comment = \Auth::user()->full_name . " Decommissioned Area ".$area->area_reference." by Decommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(AREA_TYPE, $area->id, AUDIT_ACTION_DECOMMISSION, $area->area_reference, $area->survey_id, $comment , 0 , $area->property_id ?? 0);
                            }
                        }

                        if(isset($property->locations) && count($property->locations)) {
                            foreach ($property->locations as $location) {
                                $comment = \Auth::user()->full_name . " Decommissioned Location ".$location->location_reference." by Decommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(LOCATION_TYPE, $location->id, AUDIT_ACTION_DECOMMISSION, $location->location_reference, $location->survey_id ,$comment, 0 ,$location->property_id);
                            }
                        }
                        if(isset($property->items) && count($property->items)) {
                            foreach ($property->items as $item) {
                                $comment = \Auth::user()->full_name . " Decommissioned Item ".$item->reference." by Decommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(ITEM_TYPE, $item->id, AUDIT_ACTION_DECOMMISSION, $item->reference, $item->survey_id ,$comment, 0 ,$item->property_id);
                            }
                        }

                        if(isset($property->assessment) && count($property->assessment)) {
                            foreach ($property->assessment as $assessment) {
                                $comment = \Auth::user()->full_name . " Decommission Assessment ".$assessment->reference." by Decommission Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_DECOMMISSION, $assessment->reference, $assessment->property_id ,$comment, 0 ,$assessment->property_id);
                            }
                        }

                        if(isset($property->fireExist) && count($property->fireExist)) {
                            foreach ($property->fireExist as $fireExist) {
                                $comment = \Auth::user()->full_name . " Decommissioned Fire Exist ".$fireExist->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(FIRE_EXIT_TYPE, $fireExist->id, AUDIT_ACTION_DECOMMISSION, $fireExist->reference, $fireExist->assess_id ,$comment, 0 ,$fireExist->property_id);
                            }
                        }

                        if(isset($property->assemblyPoint) && count($property->assemblyPoint)) {
                            foreach ($property->assemblyPoint as $assemblyPoint) {
                                $comment = \Auth::user()->full_name . " Decommissioned Assembly Point Exist ".$assemblyPoint->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(ASSEMBLY_POINT, $assemblyPoint->id, AUDIT_ACTION_DECOMMISSION, $assemblyPoint->reference, $assemblyPoint->assess_id ,$comment, 0 ,$assemblyPoint->property_id);
                            }
                        }

                        if(isset($property->vehicleParking) && count($property->vehicleParking)) {
                            foreach ($property->vehicleParking as $vehicleParking) {
                                $comment = \Auth::user()->full_name . " Decommissioned Vehicle Parking ".$vehicleParking->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(VEHICLE_PARKING_TYPE, $vehicleParking->id, AUDIT_ACTION_DECOMMISSION, $vehicleParking->reference, $vehicleParking->assess_id ,$comment, 0 ,$vehicleParking->property_id);
                            }
                        }

                        if(isset($property->system) && count($property->system)) {
                            foreach ($property->system as $system) {
                                $comment = \Auth::user()->full_name . " Decommissioned System ".$system->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(SYSTEM_TYPE, $system->id, AUDIT_ACTION_DECOMMISSION, $system->reference, $system->assess_id ,$comment, 0 ,$system->property_id);
                            }
                        }

                        if(isset($property->equipment) && count($property->equipment)) {
                            foreach ($property->equipment as $equipment) {
                                $comment = \Auth::user()->full_name . " Decommissioned Equipment ".$equipment->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_DECOMMISSION, $equipment->reference, $equipment->assess_id ,$comment, 0 ,$equipment->property_id);
                            }
                        }

                        if(isset($property->programme) && count($property->programme)) {
                            foreach ($property->programme as $programme) {
                                $comment = \Auth::user()->full_name . " Decommissioned Programme ".$programme->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(PROGRAMME_TYPE, $programme->id, AUDIT_ACTION_DECOMMISSION, $programme->reference, $programme->assess_id ,$comment, 0 ,$programme->property_id);
                            }
                        }

                        if(isset($property->hazard) && count($property->hazard)) {
                            foreach ($property->hazard as $hazard) {
                                $comment = \Auth::user()->full_name . " Decommissioned Hazard ".$hazard->reference." by Decommissioned Group " . $zone->zone_name;
                                \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_DECOMMISSION, $hazard->reference, $hazard->assess_id ,$comment, 0 ,$hazard->property_id);
                            }
                        }
                    }
                }

                $response = \CommonHelpers::successResponse('Property Group Decommissioned Successfully!');
            }
            \DB::commit();
            return $response;
        } catch (\Exception $e) {
            dd($e);
            \Log::debug($e);
            \DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Update Property Fail!');
        }
    }

    public function getRegisterOverallSummary($property_ids,$zone_id, $asbestos = true, $fire = true, $water = true, $hs = true){
        $data = [];
                // asbestos risk
        $register_summary = $this->getRegisterSummary($property_ids, 0 ,route('shineCompliance.zone.asbestos',['zone_id' => $zone_id]));
        if($asbestos) {
            $data['Total Risk Count']['asbestos']['count'] = $register_summary['All ACM Items']['number'];
            $data['Total Risk Count']['asbestos']['link'] = $register_summary['All ACM Items']['link'];
            $data['Inaccessible ACM Item Summary']['asbestos']['count'] = $register_summary['Inaccessible ACM Item Summary']['number'];
            $data['Inaccessible ACM Item Summary']['asbestos']['link'] = $register_summary['Inaccessible ACM Item Summary']['link'];
            $data['Very High Risk Summary']['asbestos']['count'] = 'N/A';
            $data['Very High Risk Summary']['asbestos']['link'] = '#';
            $data['High Risk Summary']['asbestos']['count'] = $register_summary['High Risk ACM Item Summary']['number'];
            $data['High Risk Summary']['asbestos']['link'] = $register_summary['High Risk ACM Item Summary']['link'];
            $data['Medium Risk Summary']['asbestos']['count'] = $register_summary['Medium Risk ACM Item Summary']['number'];
            $data['Medium Risk Summary']['asbestos']['link'] = $register_summary['Medium Risk ACM Item Summary']['link'];
            $data['Low Risk Summary']['asbestos']['count'] = $register_summary['Low Risk ACM Item Summary']['number'];
            $data['Low Risk Summary']['asbestos']['link'] = $register_summary['Low Risk ACM Item Summary']['link'];
            $data['Very Low Risk Summary']['asbestos']['count'] = $register_summary['Very Low Risk ACM Item Summary']['number'];
            $data['Very Low Risk Summary']['asbestos']['link'] = $register_summary['Very Low Risk ACM Item Summary']['link'];
            $data['No Risk (NoACM) Summary']['asbestos']['count'] = $register_summary['No Risk (NoACM) Item Summary']['number'];
            $data['No Risk (NoACM) Summary']['asbestos']['link'] = $register_summary['No Risk (NoACM) Item Summary']['link'];
            // for all type
            $data['Inaccessible Room/locations Summary']['asbestos']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['asbestos']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }

        if($fire) {
            // hazard fire risk
            $fire_hazard = $this->getHazardRegisterSummary($property_ids, ASSESSMENT_FIRE_TYPE, 0, route('shineCompliance.zone.fire',['zone_id' => $zone_id]));

            $data['Total Risk Count']['fire']['count'] = $fire_hazard['All Hazard Risk Count']['number'];
            $data['Total Risk Count']['fire']['link'] = $fire_hazard['All Hazard Risk Count']['link'];
            $data['Inaccessible ACM Item Summary']['fire']['count'] = 'N/A';
            $data['Inaccessible ACM Item Summary']['fire']['link'] = '#';
            $data['Very High Risk Summary']['fire']['count'] = $fire_hazard['Very High Risk Hazard Summary']['number'];
            $data['Very High Risk Summary']['fire']['link'] = $fire_hazard['Very High Risk Hazard Summary']['link'];
            $data['High Risk Summary']['fire']['count'] = $fire_hazard['High Risk Hazard Summary']['number'];
            $data['High Risk Summary']['fire']['link'] = $fire_hazard['High Risk Hazard Summary']['link'];
            $data['Medium Risk Summary']['fire']['count'] = $fire_hazard['Medium Risk Hazard Summary']['number'];
            $data['Medium Risk Summary']['fire']['link'] = $fire_hazard['Medium Risk Hazard Summary']['link'];
            $data['Low Risk Summary']['fire']['count'] = $fire_hazard['Low Risk Hazard Summary']['number'];
            $data['Low Risk Summary']['fire']['link'] = $fire_hazard['Low Risk Hazard Summary']['link'];
            $data['Very Low Risk Summary']['fire']['count'] = $fire_hazard['Very Low Risk Hazard Summary']['number'];
            $data['Very Low Risk Summary']['fire']['link'] = $fire_hazard['Very Low Risk Hazard Summary']['link'];
            $data['No Risk (NoACM) Summary']['fire']['count'] = 'N/A';
            $data['No Risk (NoACM) Summary']['fire']['link'] = '#';
            $data['Inaccessible Room/locations Summary']['fire']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['fire']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }

        if($hs){
            $hs_hazard = $this->getHazardRegisterSummary($property_ids, ASSESSMENT_HS_TYPE, 0 , route('shineCompliance.zone.health_and_safety',['zone_id' => $zone_id]));
            // gas risk
            $data['Total Risk Count']['hs']['count'] = $hs_hazard['All Hazard Risk Count']['number'];
            $data['Total Risk Count']['hs']['link'] = $hs_hazard['All Hazard Risk Count']['link'];
            $data['Inaccessible ACM Item Summary']['hs']['count'] = 'N/A';
            $data['Inaccessible ACM Item Summary']['hs']['link'] = '#';
            $data['Very High Risk Summary']['hs']['count'] = $hs_hazard['Very High Risk Hazard Summary']['number'];
            $data['Very High Risk Summary']['hs']['link'] = $hs_hazard['Very High Risk Hazard Summary']['link'];
            $data['High Risk Summary']['hs']['count'] = $hs_hazard['High Risk Hazard Summary']['number'];
            $data['High Risk Summary']['hs']['link'] = $hs_hazard['High Risk Hazard Summary']['link'];
            $data['Medium Risk Summary']['hs']['count'] = $hs_hazard['Medium Risk Hazard Summary']['number'];
            $data['Medium Risk Summary']['hs']['link'] = $hs_hazard['Medium Risk Hazard Summary']['link'];
            $data['Low Risk Summary']['hs']['count'] = $hs_hazard['Low Risk Hazard Summary']['number'];
            $data['Low Risk Summary']['hs']['link'] = $hs_hazard['Low Risk Hazard Summary']['link'];
            $data['Very Low Risk Summary']['hs']['count'] = $hs_hazard['Very Low Risk Hazard Summary']['number'];
            $data['Very Low Risk Summary']['hs']['link'] = $hs_hazard['Very Low Risk Hazard Summary']['link'];
            $data['No Risk (NoACM) Summary']['hs']['count'] = 'N/A';
            $data['No Risk (NoACM) Summary']['hs']['link'] = '#';
            $data['Inaccessible Room/locations Summary']['hs']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['hs']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }
        if($water) {
        // hazard water risk
            $water_hazard = $this->getHazardRegisterSummary($property_ids, ASSESSMENT_WATER_TYPE, 0, route('shineCompliance.zone.water',['zone_id' => $zone_id]));

            $data['Total Risk Count']['water']['count'] = $water_hazard['All Hazard Risk Count']['number'];
            $data['Total Risk Count']['water']['link'] = $water_hazard['All Hazard Risk Count']['link'];
            $data['Inaccessible ACM Item Summary']['water']['count'] = 'N/A';
            $data['Inaccessible ACM Item Summary']['water']['link'] = '#';
            $data['Very High Risk Summary']['water']['count'] = $water_hazard['Very High Risk Hazard Summary']['number'];
            $data['Very High Risk Summary']['water']['link'] = $water_hazard['Very High Risk Hazard Summary']['link'];
            $data['High Risk Summary']['water']['count'] = $water_hazard['High Risk Hazard Summary']['number'];
            $data['High Risk Summary']['water']['link'] = $water_hazard['High Risk Hazard Summary']['link'];
            $data['Medium Risk Summary']['water']['count'] = $water_hazard['Medium Risk Hazard Summary']['number'];
            $data['Medium Risk Summary']['water']['link'] = $water_hazard['Medium Risk Hazard Summary']['link'];
            $data['Low Risk Summary']['water']['count'] = $water_hazard['Low Risk Hazard Summary']['number'];
            $data['Low Risk Summary']['water']['link'] = $water_hazard['Low Risk Hazard Summary']['link'];
            $data['Very Low Risk Summary']['water']['count'] = $water_hazard['Very Low Risk Hazard Summary']['number'];
            $data['Very Low Risk Summary']['water']['link'] = $water_hazard['Very Low Risk Hazard Summary']['link'];
            $data['No Risk (NoACM) Summary']['water']['count'] = 'N/A';
            $data['No Risk (NoACM) Summary']['water']['link'] = '#';
            $data['Inaccessible Room/locations Summary']['water']['count'] = $register_summary['Inaccessible Room/locations Summary']['number'];
            $data['Inaccessible Room/locations Summary']['water']['link'] = $register_summary['Inaccessible Room/locations Summary']['link'];
        }
        return $data;
    }

    public function countAllInaccessibleRooms($client_id,$decommissioned) {
        $condition = "";
        if ($client_id != 0) {
            $condition = "AND p.client_id = $client_id";
        }
        $sql = "SELECT count(l.id) as countId FROM `tbl_location` as l
                        LEFT JOIN tbl_property as p ON p.id = l.property_id
                        WHERE (l.survey_id = 0)
                        $condition
                        AND (l.assess_id = 0)
                        AND (l.decommissioned = $decommissioned)
                        AND (l.`state` = 0)";
        $results = collect(DB::select($sql));
        return $results;
    }
    public function getRegisterSummary($property_ids, $decommissioned = 0, $source = null) {

        $item =  $this->itemRepository->getItemsByPropertyIds($property_ids,$decommissioned);

        $counterAll = 0;
        $counterAll += $highRisk = $this->countRiskItem($item, 'high', $decommissioned);
        $counterAll += $mediumRisk = $this->countRiskItem($item, 'medium', $decommissioned);
        $counterAll += $lowRisk = $this->countRiskItem($item, 'low', $decommissioned);
        $counterAll += $vlowRisk = $this->countRiskItem($item, 'vlow', $decommissioned);

        $counterAll += $inaccessibleItems = $item->where('state', ITEM_INACCESSIBLE_STATE)->count();
        $noACMItems =  $item->where('state', ITEM_NOACM_STATE)->count();

        $inaccessibleRooms = count($this->countInaccessibleRooms($property_ids, $decommissioned));

        $source = $source ?? url()->full();
        $dataSummary = [
            "All ACM Items" => [
                'number' => $counterAll,
                'link' => $source. '?section='. TYPE_All_ACM_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Inaccessible ACM Item Summary" => [
                'number' => $inaccessibleItems,
                'link' => $source. '?section='. TYPE_INACCESS_ACM_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "High Risk ACM Item Summary" => [
                'number' => $highRisk,
                'link' => $source. '?section='. TYPE_HIGH_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Medium Risk ACM Item Summary" =>  [
                'number' => $mediumRisk,
                'link' => $source. '?section='. TYPE_MEDIUM_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Low Risk ACM Item Summary" => [
                'number' => $lowRisk,
                'link' => $source. '?section='. TYPE_LOW_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "Very Low Risk ACM Item Summary" => [
                'number' => $vlowRisk,
                'link' => $source. '?section='. TYPE_VERY_LOW_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
            "No Risk (NoACM) Item Summary" => [
                'number' => $noACMItems,
                'link' => $source. '?section='. TYPE_NO_RISK_ITEM_SUMMARY . '&decommissioned='.$decommissioned
            ],
        ];

        $dataSummary["Inaccessible Room/locations Summary"] = [
            'number' => $inaccessibleRooms,
            'link' => $source. '?section='. TYPE_INACCESS_ROOM_SUMMARY . '&decommissioned='.$decommissioned
        ];


        return $dataSummary;
    }

    public function countRiskItem($item, $type, $decommissioned){
        if (is_null($item)) {
            return [];
        }
        switch ($type) {
            case 'high':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[10, 99])->count();
                break;

            case 'medium':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[7, 9])->count();
                break;

            case 'low':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[5, 6])->count();
                break;

            case 'vlow':
                $items = $item->where('decommissioned', $decommissioned)->where('state', ITEM_ACCESSIBLE_STATE)->whereBetween('total_mas_risk',[0, 4])->count();
                break;

            default:
                return 0;
                break;
        }

        return $items;
    }

    public function countInaccessibleRooms($property_ids, $decommissioned) {

        $locations = Location::with('allItems', 'items')
            ->whereIn('property_id', $property_ids)
            ->where('survey_id', 0)
            ->where('assess_id', 0)
            ->where('state', LOCATION_STATE_INACCESSIBLE)
            ->where('decommissioned', $decommissioned)->get();
        return $locations;
    }

    public function countRiskHazard($property_id, $type, $assess_type, $decommission = 0){

        switch ($type) {
            case 'vhigh':

                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[21, 25])->count();

                break;

            case 'high':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[16, 20])->count();
                break;

            case 'medium':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[10, 15])->count();
                break;

            case 'low':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[4, 9])->count();
                break;

            case 'vlow':
                $hazards = Hazard::where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[0, 3])->count();
                break;

            default:
                return 0;
                break;
        }

        return $hazards;
    }

    public function getHazardRegisterSummary($property_id, $assess_type, $decommission = 0, $source = null) {
        $counterAll = 0;
        $counterAll += $vhighRisk = $this->countRiskHazard($property_id, 'vhigh', $assess_type, $decommission);
        $counterAll += $highRisk = $this->countRiskHazard($property_id, 'high', $assess_type, $decommission);
        $counterAll += $mediumRisk = $this->countRiskHazard($property_id, 'medium', $assess_type, $decommission);
        $counterAll += $lowRisk = $this->countRiskHazard($property_id, 'low', $assess_type, $decommission);
        $counterAll += $vlowRisk = $this->countRiskHazard($property_id, 'vlow', $assess_type, $decommission);

        $source = $source ?? url()->full();
        $dataSummary = [
            "All Hazard Risk Count" => [
                'number' => $counterAll,
                'link' => $source. '?section='. TYPE_ALL_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Very High Risk Hazard Summary" => [
                'number' => $vhighRisk,
                'link' => $source. '?section='. TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "High Risk Hazard Summary" => [
                'number' => $highRisk,
                'link' => $source. '?section='. TYPE_HIGH_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Medium Risk Hazard Summary" =>  [
                'number' => $mediumRisk,
                'link' => $source. '?section='. TYPE_MEDIUM_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Low Risk Hazard Summary" => [
                'number' => $lowRisk,
                'link' => $source. '?section='. TYPE_LOW_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ],
            "Very Low Risk Hazard Summary" => [
                'number' => $vlowRisk,
                'link' => $source. '?section='. TYPE_VERY_LOW_RISK_HAZARD_SUMMARY . '&decommissioned='.$decommission
            ]
        ];

        return $dataSummary;
    }

    public function getDecomissionedRegisterOverallSummary($property_ids,$zone_id) {
        $register_summary = $this->getRegisterSummary($property_ids, 1 ,route('shineCompliance.zone.asbestos',['zone_id' => $zone_id]));

        $data['ACM Summary']['number'] = $register_summary['All ACM Items']['number'];
        $data['ACM Summary']['link'] = $register_summary['All ACM Items']['link'];

        $data['NoACM Summary']['number'] = $register_summary['No Risk (NoACM) Item Summary']['number'];
        $data['NoACM Summary']['link'] = $register_summary['No Risk (NoACM) Item Summary']['link'];

        $fire_hazard = $this->getHazardRegisterSummary($property_ids, 1 , route('shineCompliance.zone.fire',['zone_id' => $zone_id]));

        $data['Fire Hazard Summary']['number'] = $fire_hazard['All Hazard Risk Count']['number'];
        $data['Fire Hazard Summary']['link'] = $fire_hazard['All Hazard Risk Count']['link'];

        $data['Gas Hazard Summary']['number'] = 0;
        $data['Gas Hazard Summary']['link'] = '#';

        $water_hazard = $this->getHazardRegisterSummary($property_ids, 1 , route('shineCompliance.zone.water',['zone_id' => $zone_id]));

        $data['Water Hazard Summary']['number'] = $water_hazard['All Hazard Risk Count']['number'];
        $data['Water Hazard Summary']['link'] = $water_hazard['All Hazard Risk Count']['link'];

        return $data;
    }

    public function getRiskItem($property_ids, $type, $decommissioned = 0){
        $decommissioned_text = $decommissioned == 0 ? '' : 'Decommissioned';
        $breadcrumb = '';

        switch ($type) {
            case TYPE_HIGH_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])
                    ->where('decommissioned', $decommissioned)
                    ->whereIn('property_id', $property_ids)
                    ->where('survey_id', 0)
                    ->where('state', ITEM_ACCESSIBLE_STATE)
                    ->whereBetween('total_mas_risk',[10, 99])->get();
                $breadcrumb = "$decommissioned_text High Risk ACM Item Summary";
                break;

            case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])
                    ->where('decommissioned', $decommissioned)
                    ->where('survey_id', 0)
                    ->whereIn('property_id', $property_ids)
                    ->where('state', ITEM_ACCESSIBLE_STATE)
                    ->whereBetween('total_mas_risk',[7, 9])->get();
                $breadcrumb = "$decommissioned_text Medium Risk ACM Item Summary";
                break;

            case TYPE_LOW_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                    ->whereIn('property_id', $property_ids)
                    ->where('survey_id', 0)
                    ->where('state', ITEM_ACCESSIBLE_STATE)
                    ->whereBetween('total_mas_risk',[5, 6])->get();
                $breadcrumb = "$decommissioned_text Low Risk ACM Item Summary";
                break;

            case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])
                    ->where('decommissioned', $decommissioned)
                    ->whereIn('property_id', $property_ids)
                    ->where('survey_id', 0)
                    ->where('state', ITEM_ACCESSIBLE_STATE)
                    ->whereBetween('total_mas_risk',[0, 4])->get();
                $breadcrumb = "$decommissioned_text Very Low Risk ACM Item Summary";
                break;

            case TYPE_INACCESS_ROOM_SUMMARY:
                $data = $this->countInaccessibleRooms($property_ids, $decommissioned);
                $breadcrumb = "$decommissioned_text Inaccessible Room/locations Summary";
                break;

            case TYPE_NO_RISK_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                    ->whereIn('property_id', $property_ids)
                    ->where('survey_id', 0)
                    ->where('state', ITEM_NOACM_STATE)->get();
                $breadcrumb = "$decommissioned_text No Risk ACM Item Summary";
                break;

            case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                    ->whereIn('property_id', $property_ids)
                    ->where('survey_id', 0)
                    ->where('state', ITEM_INACCESSIBLE_STATE)
                    ->get();
                $breadcrumb = "$decommissioned_text Inaccessible ACM Item Summary";
                break;

            case TYPE_All_ACM_ITEM_SUMMARY:
                $data = Item::with(['area', 'location'])->where('decommissioned', $decommissioned)
                    ->whereIn('property_id', $property_ids)
                    ->where('survey_id', 0)
                    ->where('state', '!=' ,ITEM_NOACM_STATE)
                    ->get();

                $breadcrumb = "$decommissioned_text All ACM Risk Count";
                break;

            default:
                $data = [];
                $breadcrumb = '';
                break;
        }

        return [
            'data' => $data ?? [],
            'breadcrumb' => $breadcrumb,
        ];
    }

    public function getRiskHazard($property_id, $type,$assess_type, $decommission = 0){
        $breadcrumb = '';
        $hazards = [];
        $decommissioned_text = $decommission == 0 ? '' : 'Decommissioned';
        switch ($type) {
            case TYPE_ALL_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->get();
                $breadcrumb = "$decommissioned_text All Hazard Risk Summary";
                break;

            case TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[21, 25])->get();
                $breadcrumb = "$decommissioned_text Very High Risk Hazard Summary";
                break;

            case TYPE_HIGH_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[16, 20])->get();
                $breadcrumb = "$decommissioned_text High Risk Hazard Summary";
                break;

            case TYPE_MEDIUM_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[10, 15])->get();
                $breadcrumb = "$decommissioned_text Medium Risk Hazard Summary";
                break;

            case TYPE_LOW_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[4, 9])->get();
                $breadcrumb = "$decommissioned_text Low Risk Hazard Summary";
                break;

            case TYPE_VERY_LOW_RISK_HAZARD_SUMMARY:
                $hazards = Hazard::with(['area','location', 'hazardType'])->where('decommissioned', $decommission)
                                            ->whereIn('property_id', $property_id)
                                            ->where('assess_id', 0)
                                            ->where('is_deleted', 0)
                                            ->where('is_temp', 0)
                                            ->where('assess_type', $assess_type)
                                            ->whereBetween('total_risk',[0, 3])->get();
                $breadcrumb = "$decommissioned_text Very Low Risk Hazard Summary";
                break;

            default:
                $hazards = [];
                $breadcrumb = '';
                break;
        }

        return [
            'hazards' => $hazards,
            'breadcrumb' => $breadcrumb
        ];
    }

    public function getBreadcrumbSummary($type, $decommissioned = 0) {

        $decommissioned_text = $decommissioned == 0 ? '' : 'Decommissioned';

        switch ($type) {
            case TYPE_HIGH_RISK_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text High Risk ACM Item Summary";
                break;

            case TYPE_MEDIUM_RISK_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text Medium Risk ACM Item Summary";
                break;

            case TYPE_LOW_RISK_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text Low Risk ACM Item Summary";
                break;

            case TYPE_VERY_LOW_RISK_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text Very Low Risk ACM Item Summary";
                break;

            case TYPE_INACCESS_ROOM_SUMMARY:
                $breadcrumb = "$decommissioned_text Inaccessible Room/locations Summary";
                break;

            case TYPE_NO_RISK_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text No Risk ACM Item Summary";
                break;

            case TYPE_INACCESS_ACM_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text Inaccessible ACM Item Summary";
                break;

            case TYPE_All_ACM_ITEM_SUMMARY:
                $breadcrumb = "$decommissioned_text All ACM Risk Count";
                break;

            default:
                $breadcrumb = '';
                break;
        }

        return $breadcrumb;
    }


    public function getAllRiskHazard($type, $decommission = 0 ,$client_id){
        $breadcrumb = '';
        $hazards = [];
        $condition = "LEFT JOIN tbl_property as p ON h.property_id = p.id";
        if ($client_id != 0) {
            $condition = "JOIN (SELECT id,`name` FROM tbl_property WHERE client_id = $client_id) as p ON h.property_id = p.id";
        }
        $decommissioned_text = $decommission == 0 ? '' : 'Decommissioned';
        switch ($type) {
            case TYPE_ALL_HAZARD_SUMMARY:
                $sql = "SELECT
                    h.id as id,
                    h.`name` as hazards_name,
                    h.reference as hazards_reference,
                    ht.`description` as hazards_type_description,
                    h.total_risk as total_risk,
                    a.area_reference as area_reference,
                    a.description as area_description,
                    h.area_id as area_id,
                    h.location_id as location_id,
                    l.location_reference as location_reference,
                    l.description as location_description,
                    h.property_id as property_id
                    FROM `cp_hazards` as h
                    LEFT JOIN cp_hazard_type as ht ON ht.id = h.type
                    $condition
                    LEFT JOIN tbl_area as a ON h.area_id = a.id
                    LEFT JOIN tbl_location as l ON h.location_id = l.id
                    WHERE (h.assess_id = 0)
                    AND (h.is_deleted = 0)
                    AND (h.decommissioned = $decommission)";
                $hazards = collect(DB::select($sql));
                $breadcrumb = "$decommissioned_text All Hazard Risk Summary";
                break;

            case TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY:
                $sql = "SELECT
                    h.id as id,
                    h.`name` as hazards_name,
                    h.reference as hazards_reference,
                    ht.`description` as hazards_type_description,
                    h.total_risk as total_risk,
                    a.area_reference as area_reference,
                    a.description as area_description,
                    h.area_id as area_id,
                    h.location_id as location_id,
                    l.location_reference as location_reference,
                    l.description as location_description,
                    h.property_id as property_id
                    FROM `cp_hazards` as h
                    LEFT JOIN cp_hazard_type as ht ON ht.id = h.type
                    $condition
                    LEFT JOIN tbl_area as a ON h.area_id = a.id
                                        LEFT JOIN tbl_location as l ON h.location_id = l.id
                    WHERE (h.assess_id = 0)
                    AND (h.is_deleted = 0)
                    AND (h.decommissioned = $decommission)
                    AND (h.total_risk) BETWEEN 21 AND 25";
                $hazards = collect(DB::select($sql));
                $breadcrumb = "$decommissioned_text Very High Risk Hazard Summary";
                break;

            case TYPE_HIGH_RISK_HAZARD_SUMMARY:
                $sql = "SELECT
                    h.id as id,
                    h.`name` as hazards_name,
                    h.reference as hazards_reference,
                    ht.`description` as hazards_type_description,
                    h.total_risk as total_risk,
                    a.area_reference as area_reference,
                    a.description as area_description,
                    h.area_id as area_id,
                    h.location_id as location_id,
                    l.location_reference as location_reference,
                    l.description as location_description,
                    h.property_id as property_id
                    FROM `cp_hazards` as h
                    LEFT JOIN cp_hazard_type as ht ON ht.id = h.type
                    $condition
                    LEFT JOIN tbl_area as a ON h.area_id = a.id
                                        LEFT JOIN tbl_location as l ON h.location_id = l.id
                    WHERE (h.assess_id = 0)
                    AND (h.is_deleted = 0)
                    AND (h.decommissioned = $decommission)
                    AND (h.total_risk) BETWEEN 16 AND 20";
                $hazards = collect(DB::select($sql));
                $breadcrumb = "$decommissioned_text High Risk Hazard Summary";
                break;

            case TYPE_MEDIUM_RISK_HAZARD_SUMMARY:
                $sql = "SELECT
                    h.id as id,
                    h.`name` as hazards_name,
                    h.reference as hazards_reference,
                    ht.`description` as hazards_type_description,
                    h.total_risk as total_risk,
                    a.area_reference as area_reference,
                    a.description as area_description,
                    h.area_id as area_id,
                    h.location_id as location_id,
                    l.location_reference as location_reference,
                    l.description as location_description,
                    h.property_id as property_id
                    FROM `cp_hazards` as h
                    LEFT JOIN cp_hazard_type as ht ON ht.id = h.type
                    $condition
                    LEFT JOIN tbl_area as a ON h.area_id = a.id
                                        LEFT JOIN tbl_location as l ON h.location_id = l.id
                    WHERE (h.assess_id = 0)
                    AND (h.is_deleted = 0)
                    AND (h.decommissioned = $decommission)
                    AND (h.total_risk) BETWEEN 10 AND 15";
                $hazards = collect(DB::select($sql));
                $breadcrumb = "$decommissioned_text Medium Risk Hazard Summary";
                break;

            case TYPE_LOW_RISK_HAZARD_SUMMARY:
                $sql = "SELECT
                    h.id as id,
                    h.`name` as hazards_name,
                    h.reference as hazards_reference,
                    ht.`description` as hazards_type_description,
                    h.total_risk as total_risk,
                    a.area_reference as area_reference,
                    a.description as area_description,
                    h.area_id as area_id,
                    h.location_id as location_id,
                    l.location_reference as location_reference,
                    l.description as location_description,
                    h.property_id as property_id
                    FROM `cp_hazards` as h
                    LEFT JOIN cp_hazard_type as ht ON ht.id = h.type
                    L$condition
                    LEFT JOIN tbl_area as a ON h.area_id = a.id
                                        LEFT JOIN tbl_location as l ON h.location_id = l.id
                    WHERE (h.assess_id = 0)
                    AND (h.is_deleted = 0)
                    AND (h.decommissioned = $decommission)
                    AND (h.total_risk) BETWEEN 4 AND 9";
                $hazards = collect(DB::select($sql));
                $breadcrumb = "$decommissioned_text Low Risk Hazard Summary";
                break;

            case TYPE_VERY_LOW_RISK_HAZARD_SUMMARY:
                $sql = "SELECT
                    h.id as id,
                    h.`name` as hazards_name,
                    h.reference as hazards_reference,
                    ht.`description` as hazards_type_description,
                    h.total_risk as total_risk,
                    a.area_reference as area_reference,
                    a.description as area_description,
                    h.area_id as area_id,
                    h.location_id as location_id,
                    l.location_reference as location_reference,
                    l.description as location_description,
                    h.property_id as property_id
                    FROM `cp_hazards` as h
                    LEFT JOIN cp_hazard_type as ht ON ht.id = h.type
                    $condition
                    LEFT JOIN tbl_area as a ON h.area_id = a.id
                                        LEFT JOIN tbl_location as l ON h.location_id = l.id
                    WHERE (h.assess_id = 0)
                    AND (h.is_deleted = 0)
                    AND (h.decommissioned = $decommission)
                    AND (h.total_risk) BETWEEN 0 AND 3";
                $hazards = collect(DB::select($sql));
                $breadcrumb = "$decommissioned_text Very Low Risk Hazard Summary";
                break;

            default:
                $hazards = [];
                $breadcrumb = '';
                break;
        }

        return [
            'hazards' => $hazards,
            'breadcrumb' => $breadcrumb
        ];
    }

    public function getBreadcrumbHazard($type, $decommission = 0) {
        $decommissioned_text = $decommission == 0 ? '' : 'Decommissioned';
        switch ($type) {
            case TYPE_ALL_HAZARD_SUMMARY:
                $breadcrumb = "$decommissioned_text All Hazard Risk Summary";
                break;

            case TYPE_VERY_HIGH_RISK_HAZARD_SUMMARY:
                $breadcrumb = "$decommissioned_text Very High Risk Hazard Summary";
                break;

            case TYPE_HIGH_RISK_HAZARD_SUMMARY:
                $breadcrumb = "$decommissioned_text High Risk Hazard Summary";
                break;

            case TYPE_MEDIUM_RISK_HAZARD_SUMMARY:
                $breadcrumb = "$decommissioned_text Medium Risk Hazard Summary";
                break;

            case TYPE_LOW_RISK_HAZARD_SUMMARY:
                $breadcrumb = "$decommissioned_text Low Risk Hazard Summary";
                break;

            case TYPE_VERY_LOW_RISK_HAZARD_SUMMARY:
                $breadcrumb = "$decommissioned_text Very Low Risk Hazard Summary";
                break;

            default:
                $breadcrumb = '';
                break;
        }
        return $breadcrumb;
    }

    public function getAllInaccessibleRooms($client_id = 0,$decommissioned) {
        $condition = "LEFT JOIN tbl_property as p ON p.id = l.property_id";
        if ($client_id != 0) {
            $condition = "JOIN (SELECT id,`name` FROM tbl_property WHERE client_id = $client_id) as p ON l.property_id = p.id";
        }
        $sql = "SELECT l.property_id as property_id,
                l.id,
                l.location_reference,
                p.`name` as property_name,
                l.description,
                l.`state`,
                count(i.id) as all_item
                FROM `tbl_location` as l
                $condition
                LEFT JOIN tbl_items as i ON i.location_id = l.id
                WHERE (l.survey_id = 0)
                AND (l.assess_id = 0)
                AND (l.decommissioned = $decommissioned)
                AND (l.`state` = 0)
                GROUP BY l.id";
        $results = collect(DB::select($sql));
        return $results;
    }

    public function getMixedZones($text){
        return $this->zoneRepository->getMixedZones($text);
    }
}
