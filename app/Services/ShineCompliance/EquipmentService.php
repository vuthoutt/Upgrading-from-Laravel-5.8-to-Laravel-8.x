<?php


namespace App\Services\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Models\ShineCompliance\EquipmentDropdown;
use App\Models\ShineCompliance\EquipmentDropdownData;
use App\Models\ShineCompliance\EquipmentSection;
use App\Models\ShineCompliance\EquipmentSpecificLocation;
use App\Models\ShineCompliance\EquipmentTemplate;
use App\Models\ShineCompliance\EquipmentTemplateSection;
use App\Models\ShineCompliance\Hazard;
use App\Models\ShineCompliance\NonconformityValidate;
use App\Models\ShineCompliance\TempValidation;
use App\Models\ShineCompliance\TempValidationByTemplate;
use App\Repositories\ShineCompliance\EquipmentRepository;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\AssessmentRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\TempLogRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class EquipmentService
{
    private $equipmentRepository;
    private $areaRepository;
    private $locationRepository;
    private $assessmentRepository;
    private $hazardRepository;
    private $tempLogRepository;

    public function __construct(EquipmentRepository $equipmentRepository,
                                AreaRepository $areaRepository,
                                AssessmentRepository $assessmentRepository,
                                HazardRepository $hazardRepository,
                                LocationRepository $locationRepository,
                                TempLogRepository $tempLogRepository
                                )
    {
        $this->equipmentRepository = $equipmentRepository;
        $this->areaRepository = $areaRepository;
        $this->locationRepository = $locationRepository;
        $this->assessmentRepository = $assessmentRepository;
        $this->hazardRepository = $hazardRepository;
        $this->tempLogRepository = $tempLogRepository;
    }

    public function getAllTypes() {
        return $this->equipmentRepository->getAllTypes();
    }


    public function getAllTypesWaterTemperature() {
        return $this->equipmentRepository->getAllTypesWaterTemperature();
    }

    public function getEquipment($id) {
        return $this->equipmentRepository->find($id);
    }

    public function getAllEquipmentDropdowns()
    {
        return EquipmentDropdown::all();
    }

    public function getAllEquipmentDropdownData()
    {
        return EquipmentDropdownData::all();
    }

    public function getAllEquipmentSections()
    {
        return EquipmentSection::all();
    }

    public function getAllEquipmentSpecificLocations()
    {
        return EquipmentSpecificLocation::all();
    }

    public function getAllEquipmentTemplateSections()
    {
        return EquipmentTemplateSection::all();
    }

    public function getAllEquipmentTemplates()
    {
        return EquipmentTemplate::all();
    }

    public function getAllTempValidation()
    {
        return TempValidation::select([
            'id',
            'type_id',
            'tmv as tmv_fitted',
            'flow_temp_gauge_value_min as flow_temp_gauge_min',
            'flow_temp_gauge_value_max as flow_temp_gauge_max',
            'return_temp_gauge_value_min as return_temp_gauge_min',
            'return_temp_gauge_value_max as return_temp_gauge_max',
            'flow_temp_min',
            'flow_temp_max',
            'inlet_temp_min',
            'inlet_temp_max',
            'stored_temp_min',
            'stored_temp_max',
            'top_temp_min',
            'top_temp_max',
            'bottom_temp_min',
            'bottom_temp_max',
            'return_temp_min',
            'return_temp_max',
        ])->get();
    }

    public function getEquipmentDropdownData($dropdown_id) {
        return $this->equipmentRepository->getEquipmentDropdownData($dropdown_id);
    }

    public function getEquipmentSpecificLocation($parent_id = 0) {
        return $this->equipmentRepository->getEquipmentSpecificLocation($parent_id);
    }

    public function getActiveSection($type, $is_water_temperature) {
        return $this->equipmentRepository->getActiveSection($type, $is_water_temperature);
    }

    public function searchEquipment($query_string, $property_id = 0, $assess_id = 0, $templates = []) {
        return $this->equipmentRepository->searchEquipmentInAssessment($query_string, $property_id, $assess_id, $templates);
    }

    public function searchEquipmentType($query_string) {
        return $this->equipmentRepository->searchEquipmentType($query_string);
    }

    public function searchEquipmentTypeWaterTemperature($query_string) {
        return $this->equipmentRepository->searchEquipmentTypeWaterTemperature($query_string);
    }

    public function getSpecificlocationValue($equipment_id) {
        return $this->equipmentRepository->getSpecificlocationValue($equipment_id);
    }

    public function getAllEquipments($limit, $property_id = null) {

        return $this->equipmentRepository->getAllEquipments($property_id, $limit);

    }

    public function getValidateNonconformities()
    {
        return NonconformityValidate::all();
    }

    public function updateOrCreateEquipment($data, $id = null) {

        $data_area = [
            'area_reference' => $data['area_reference'] ?? null,
            'assess_id' => $data['assess_id'] ?? 0,
            'property_id' => $data['property_id'] ?? 0,
            'decommissioned' => 0,
            'survey_id' => $data['assess_id'] ? HAZARD_SURVEY_ID_DEFAULT : 0,
            'description' => $data['area_description'] ?? null
        ];
        $data_location = [
            'location_reference' => $data['location_reference'] ?? null,
            'description' => $data['location_description'] ?? null,
            'assess_id' => $data['assess_id'] ?? 0,
            'property_id' => $data['property_id'] ?? 0,
            'decommissioned' => 0,
            'survey_id' => $data['assess_id'] ? HAZARD_SURVEY_ID_DEFAULT : 0,
        ];

        // add new area , location
        if ($data['area_id'] == -1) {
            $area_id = $this->createArea($data_area);
            $data_location['area_id'] = $area_id;
            if ($data['location_id'] == -1) {
                $location_id = $this->createLocation($data_location);
            } else {
                $location_id = $data['location_id'];
            }
        } else {
            $area_id = $data['area_id'];
            $data_location['area_id'] = $area_id;
            if ($data['location_id'] == -1) {
                $location_id = $this->createLocation($data_location);
            } else {
                $location_id = $data['location_id'];
            }
        }

        $data_detail = [
            'area_id' => $area_id,
            'assess_id' => $data['assess_id'] ?? 0,
            'property_id' => $data['property_id'] ?? 0,
            'location_id' => $location_id,
            'type' => $data['type'] ?? null,
            'decommissioned' => $data['decommissioned'] ?? null,
            'state' => isset($data['state']) ? 1 : 0,
            'reason' => $data['reason'] ?? null,
            'reason_other' => $data['reason_other'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'hot_parent_id' => $data['hot_parent_id'] ?? null,
            'cold_parent_id' => $data['cold_parent_id'] ?? null,
            'system_id' => $data['system_id'] ?? null,
            'frequency_use' => $data['frequency_use'] ?? null,
            'extent' => $data['extent'] ?? null,
            'operational_use' => $data['operational_use'] ?? null,
            'name' => $data['name'] ?? null,
            'has_sample' => $data['has_sample'] ?? null,
            'sample_reference' => $data['sample_reference'] ?? null,
        ];

        $data_model = [
            'manufacturer' => $data['manufacturer'] ?? null,
            'model' => $data['model'] ?? null,
            'dimensions_length' => $data['dimensions_length'] ?? null,
            'dimensions_width' => $data['dimensions_width'] ?? null,
            'dimensions_depth' => $data['dimensions_depth'] ?? null,
            'capacity' => $data['capacity'] ?? null,
            'stored_water' => $data['stored_water'] ?? null,
        ];

        $data_construction = [
            'access' => $data['access'] ?? null,
            'water_meter_fitted' => isset($data['water_meter_fitted']) ? 1 : 0,
            'water_meter_reading' => $data['water_meter_reading'] ?? null,
            'material_of_pipework' => \ComplianceHelpers::getMultiselectData($data['material_of_pipework'] ?? null),
            'material_of_pipework_other' => $data['material_of_pipework-other'] ?? null,
            'size_of_pipework' => $data['size_of_pipework'] ?? null,
            'condition_of_pipework' => $data['condition_of_pipework'] ?? null,
            'stop_tap_fitted' => isset($data['stop_tap_fitted']) ? 1 : 0,
            'anti_stratification' => isset($data['anti_stratification']) ? 1 : 0,
            'can_isolated' => isset($data['can_isolated']) ? 1 : 0,
            'direct_fired' => $data['direct_fired'] ?? 0,
            'flexible_hose' => isset($data['flexible_hose']) ? 1 : 0,
            'horizontal_vertical' => $data['horizontal_vertical'] ?? null,
            'water_softener' => isset($data['water_softener']) ? 1 : 0,
            'insulation_type' => \ComplianceHelpers::getMultiselectData($data['insulation_type'] ?? null),
            'rodent_protection' =>isset($data['rodent_protection']) ? 1 : 0,
            'sentinel' => isset($data['sentinel']) ? 1 : 0,
            'nearest_furthest' => $data['nearest_furthest'] ?? null,
            'system_recirculated' => isset($data['system_recirculated']) ? 1 : 0,
            'screened_lid_vent' => isset($data['screened_lid_vent']) ? 1 : 0,
            'tmv_fitted' => isset($data['tmv_fitted']) ? 1 : 0,
            'warning_pipe' => isset($data['warning_pipe']) ? 1 : 0,
            'overflow_pipe' => isset($data['overflow_pipe']) ? 1 : 0,
            'backflow_protection' => isset($data['backflow_protection']) ? 1 : 0,
            'drain_size' => $data['drain_size'] ?? null,
            'drain_location' => $data['drain_location'] ?? null,
            'cold_feed_size' => $data['cold_feed_size'] ?? null,
            'cold_feed_location' => $data['cold_feed_location'] ?? null,
            'outlet_size' => $data['outlet_size'] ?? null,
            'outlet_location' => $data['outlet_location'] ?? null,
            'labelling' => \ComplianceHelpers::getMultiselectData($data['labelling'] ?? null),
            'aerosol_risk' => $data['aerosol_risk'] ?? null,
            'pipe_insulation' => \ComplianceHelpers::getMultiselectData($data['pipe_insulation'] ?? null),
            'pipe_insulation_condition' => $data['pipe_insulation_condition'] ?? null,
            'construction_material' => \ComplianceHelpers::getMultiselectData($data['construction_material'] ?? null),
            'insulation_thickness' => $data['insulation_thickness'] ?? null,
            'insulation_condition' => $data['insulation_condition'] ?? null,
            'drain_valve' => isset($data['drain_valve']) ? 1 : 0,
            'source' => $data['source'] ?? null,
            'source_accessibility' => $data['source_accessibility'] ?? null,
            'source_condition' => $data['source_condition'] ?? null,
            'air_vent' => isset($data['air_vent']) ? 1 : 0,
            'main_access_hatch' => isset($data['main_access_hatch']) ? 1 : 0,
            'ball_valve_hatch' => isset($data['ball_valve_hatch']) ? 1 : 0,
            'flow_temp_gauge' => isset($data['flow_temp_gauge']) ? 1 : 0,
            'return_temp_gauge' => isset($data['return_temp_gauge']) ? 1 : 0,
            'construction_return_temp' => isset($data['construction_return_temp']) ? 1 : 0,
        ];

        $data_cleaning = [
            'operational_exposure' => $data['operational_exposure'] ?? null,
            'envidence_stagnation' => $data['envidence_stagnation'] ?? null,
            'degree_fouling' => $data['degree_fouling'] ?? null,
            'degree_biological' => $data['degree_biological'] ?? null,
            'extent_corrosion' => $data['extent_corrosion'] ?? null,
            'cleanliness' => $data['cleanliness'] ?? null,
            'ease_cleaning' => $data['ease_cleaning'] ?? null,
            'comments' => $data['comments'] ?? null,
        ];

        $data_temp = [
            'return_temp' => $data['return_temp'] ?? null,
            'flow_temp' => $data['flow_temp'] ?? null,
            'inlet_temp' => $data['inlet_temp'] ?? null,
            'stored_temp' => $data['stored_temp'] ?? null,
            'top_temp' => $data['top_temp'] ?? null,
            'bottom_temp' => $data['bottom_temp'] ?? null,
            'flow_temp_gauge_value' => $data['flow_temp_gauge_value'] ?? null,
            'return_temp_gauge_value' => $data['return_temp_gauge_value'] ?? null,
            'ambient_area_temp' => $data['ambient_area_temp'] ?? null,
            'incoming_main_pipe_work_temp' => $data['incoming_main_pipe_work_temp'] ?? null,
            'cold_flow_temp' => $data['cold_flow_temp'] ?? null,
            'hot_flow_temp' => $data['hot_flow_temp'] ?? null,
            'pre_tmv_cold_flow_temp' => $data['pre_tmv_cold_flow_temp'] ?? null,
            'pre_tmv_hot_flow_temp' => $data['pre_tmv_hot_flow_temp'] ?? null,
            'post_tmv_temp' => $data['post_tmv_temp'] ?? null,
            'mixed_temp' => $data['mixed_temp'] ?? null,
            'ph' => $data['ph'] ?? null
        ];

        try {
            \DB::beginTransaction();
            // update equipment
            if ($id) {
                $data_detail['updated_by'] = \Auth::user()->id;
                $this->equipmentRepository->update($data_detail, $id);
                $equipment =  $this->equipmentRepository->find($id);
                $message = 'Updated Equipment Successfully!';
                //audit
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_EDIT, $equipment->reference, 0, null , 0 , $equipment->property_id ?? 0);
            // create equipment
            } else {

                $equipment = $this->equipmentRepository->create($data_detail);
                $id = $equipment->id;

                $data_update['reference'] = 'EQ' . $id;
                $data_update['created_by'] = \Auth::user()->id;
                $data_update['record_id'] = $id;
                $data_update['nonconformities'] = 0;
                $this->equipmentRepository->update($data_update, $id);
                $message = 'Created New Equipment Successfully!';
                //audit
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_ADD, $equipment->reference, 0, null , 0 , $equipment->property_id ?? 0);
            }
            //details specific location
            if (isset($data['specificLocations_other']) and $data['specificLocations_other'] != '') {
                $this->equipmentRepository->insertDropdownValue($id, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'),\CommonHelpers::checkArrayKey($data,'specificLocations_other'));
            } else {
                if (is_null(\CommonHelpers::checkArrayKey($data,'specificLocations3'))) {
                    $this->equipmentRepository->insertDropdownValue($id, 0,\CommonHelpers::checkArrayKey($data,'specificLocations1'));
                } else {
                    $this->equipmentRepository->insertDropdownValue($id, 0,\CommonHelpers::checkArrayKey($data,'specificLocations3'));
                }
            }
            // update relation
            $this->equipmentRepository->updateOrCreateModel($id, $data_model);
            $this->equipmentRepository->updateOrCreateConstruction($id, $data_construction);
            $this->equipmentRepository->updateOrCreateCleaning($id, $data_cleaning);
            $this->equipmentRepository->updateOrCreateTemp($id, $data_temp);

            if (isset($data['photoLocation'])) {
                $img = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['photoLocation'], $id, EQUIPMENT_LOCATION_PHOTO);
            }
            if (isset($data['photoEquipment'])) {
                $img = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['photoEquipment'], $id, EQUIPMENT_PHOTO);
            }
            if (isset($data['photoAdditional'])) {
                $img = \ComplianceHelpers::saveFileComplianceDocumentStorage($data['photoAdditional'], $id, EQUIPMENT_ADDITION_PHOTO);
            }

            $this->createNonConformity($equipment);
            //log temperature
            $assess_id = $data['assess_id'] ?? 0;
            if($assess_id == 0){
                $data_log = $data_temp;
                $data_log['created_by'] = \Auth::user()->id;
                $data_log['assess_id'] = $assess_id;
                $data_log['equipment_id'] = $equipment->id;
                $this->tempLogRepository->create($data_log);
            }

            \DB::commit();
            return \ComplianceHelpers::successResponse($message, $equipment);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
    }

    public function updateTemperature($request, $equipment){
        $active_key = $request->active_key;
        $value_active_key = $request->{$active_key};
        try {
            \DB::beginTransaction();
            $assess_id = $data['assess_id'] ?? 0;
            $data_temp = [
                "$active_key" => $value_active_key
            ];
            $this->equipmentRepository->updateOrCreateTemp($equipment->id, $data_temp);
            $this->createNonConformity($equipment);
            //audit
            $comment = \Auth::user()->full_name . " updated temperature on equipment " . $equipment->reference;
            \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_EDIT, $equipment->reference, 0, $comment , 0 , $equipment->property_id ?? 0);
            //log temperature
            if($assess_id == 0){
                $data_log = $data_temp;
                $data_log['created_by'] = \Auth::user()->id;
                $data_log['assess_id'] = $assess_id;
                $data_log['equipment_id'] = $equipment->id;
                $this->tempLogRepository->create($data_log);
            }

            $message = 'Updated Temperature Successfully!';
            \DB::commit();
            return \ComplianceHelpers::successResponse($message, $equipment);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            return \ComplianceHelpers::failResponse(STATUS_FAIL,'Error has occurred. Please try again later!');
        }
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

    public function createNonConformity($equipment) {

        $template_id = $equipment->equipmentType->template_id ?? 0;

        // // remove old alternative hazard and non conform if template change
        $fields = $this->equipmentRepository->getTemplateValidationFields($template_id);
        $this->equipmentRepository->removeNonconformAfterChangeType($fields,$equipment->id);

        // detail
        $this->checkNonconformity($equipment ?? null, $template_id, 'state', $equipment->state);
        $this->checkNonconformity($equipment ?? null, $template_id, 'frequency_use', $equipment->frequency_use);
        $this->checkNonconformity($equipment ?? null, $template_id, 'operational_use', $equipment->operational_use);
        $this->checkNonconformity($equipment ?? null, $template_id, 'type', $equipment->type, true);

        // cleanning
        $this->checkNonconformity($equipment ?? null, $template_id, 'envidence_stagnation', $equipment->cleaning->envidence_stagnation ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'degree_fouling', $equipment->cleaning->degree_fouling ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'degree_biological', $equipment->cleaning->degree_biological ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'extent_corrosion', $equipment->cleaning->extent_corrosion ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'cleanliness', $equipment->cleaning->cleanliness ?? null);

        // new update for insulation_type and pipe_insulation
        $insulation_type = $equipment->equipmentConstruction->insulation_type ?? '';
        $insulation_type = explode(",",$insulation_type);

        // new update for insulation_type
        $pipe_insulation = $equipment->equipmentConstruction->pipe_insulation ?? '';
        $pipe_insulation = explode(",",$pipe_insulation);

        // construction
        $this->checkNonconformity($equipment ?? null, $template_id, 'aerosol_risk', $equipment->equipmentConstruction->aerosol_risk ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'air_vent', $equipment->equipmentConstruction->air_vent ?? 0);
        $this->checkNonconformity($equipment ?? null, $template_id, 'anti_stratification', $equipment->equipmentConstruction->anti_stratification ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'ball_valve_hatch', $equipment->equipmentConstruction->ball_valve_hatch ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'can_isolated', $equipment->equipmentConstruction->can_isolated ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'direct_fired', $equipment->equipmentConstruction->direct_fired ?? 0);
        $this->checkNonconformity($equipment ?? null, $template_id, 'drain_valve', $equipment->equipmentConstruction->drain_valve ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'insulation_type', $equipment->equipmentConstruction->insulation_type ?? null);

        // if insulation_type contains None , N/A , Not Determined
        if (in_array(221, $insulation_type) || in_array(256, $insulation_type) || in_array(257, $insulation_type)) {
            // remove non conformity
            $this->checkNonconformity($equipment ?? null, $template_id, 'insulation_condition', null);
            $this->checkNonconformity($equipment ?? null, $template_id, 'insulation_thickness', null);
        } else {
            $this->checkNonconformity($equipment ?? null, $template_id, 'insulation_condition', $equipment->equipmentConstruction->insulation_condition ?? null);
            $this->checkNonconformity($equipment ?? null, $template_id, 'insulation_thickness', $equipment->equipmentConstruction->insulation_thickness ?? null);
        }

        $this->checkNonconformity($equipment ?? null, $template_id, 'main_access_hatch', $equipment->equipmentConstruction->main_access_hatch ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'pipe_insulation', $equipment->equipmentConstruction->pipe_insulation ?? null);

        // if pipe_insulation contains None , N/A , Not Determined
        if (in_array(222, $pipe_insulation) || in_array(260, $pipe_insulation) || in_array(261, $pipe_insulation)) {
            // remove non conformity
            $this->checkNonconformity($equipment ?? null, $template_id, 'pipe_insulation_condition', null);
        } else {
            $this->checkNonconformity($equipment ?? null, $template_id, 'pipe_insulation_condition', $equipment->equipmentConstruction->pipe_insulation_condition ?? null);
        }

        $this->checkNonconformity($equipment ?? null, $template_id, 'rodent_protection', $equipment->equipmentConstruction->rodent_protection ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'source_accessibility', $equipment->equipmentConstruction->source_accessibility ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'source_condition', $equipment->equipmentConstruction->source_condition ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'tank_lid', $equipment->equipmentConstruction->tank_lid ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'warning_pipe', $equipment->equipmentConstruction->warning_pipe ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'backflow_protection', $equipment->equipmentConstruction->backflow_protection ?? null);
        $this->checkNonconformity($equipment ?? null, $template_id, 'screened_lid_vent', $equipment->equipmentConstruction->screened_lid_vent ?? 0);
        $this->checkNonconformity($equipment ?? null, $template_id, 'overflow_pipe', $equipment->equipmentConstruction->overflow_pipe ?? 0);
        $this->checkNonconformity($equipment ?? null, $template_id, 'condition_of_pipework', $equipment->equipmentConstruction->condition_of_pipework ?? null);

        // temp
        $this->checkNonconformity($equipment ?? null,$template_id, 'bottom_temp', $equipment->tempAndPh->bottom_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'flow_temp', $equipment->tempAndPh->flow_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'flow_temp_gauge_value', $equipment->tempAndPh->flow_temp_gauge_value ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'inlet_temp', $equipment->tempAndPh->inlet_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'return_temp', $equipment->tempAndPh->return_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'stored_temp', $equipment->tempAndPh->stored_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'top_temp', $equipment->tempAndPh->top_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'return_temp_gauge_value', $equipment->tempAndPh->return_temp_gauge_value ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'incoming_main_pipe_work_temp', $equipment->tempAndPh->incoming_main_pipe_work_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'pre_tmv_cold_flow_temp', $equipment->tempAndPh->pre_tmv_cold_flow_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'pre_tmv_hot_flow_temp', $equipment->tempAndPh->pre_tmv_hot_flow_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'post_tmv_temp', $equipment->tempAndPh->post_tmv_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'cold_flow_temp', $equipment->tempAndPh->cold_flow_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'hot_flow_temp', $equipment->tempAndPh->hot_flow_temp ?? null);
        $this->checkNonconformity($equipment ?? null,$template_id, 'ph', $equipment->tempAndPh->ph ?? null);
    }

    public function checkNonconformity($equipment, $template_id, $field, $value, $is_equipment_type = false) {
        if ($is_equipment_type) {
            $validation = $this->equipmentRepository->getNonConformityValidate($template_id, $field, $equipment->equipmentConstruction->tmv_fitted ?? 0, $value);
        } else {
            $validation = $this->equipmentRepository->getNonConformityValidate($template_id, $field, $equipment->equipmentConstruction->tmv_fitted ?? 0);
        }
        if (!is_null($validation)) {
            $new_conform = false;
            $hazard_id = 0;
            $data_conformity = [
                'equipment_id' => $equipment->id ?? 0,
                'property_id' => $equipment->property_id ?? 0,
                'assess_id' => $equipment->assess_id ?? 0,
                'type' => $validation->nonconform_type,
                'field' => $validation->field
            ];

            switch ($validation->answer_type) {
                case 'boolean':

                    if ($value == $validation->value) {
                        $new_conform = true;
                    }
                    break;

                // in selected value
                case 'dropdown':
                    $validation_value = explode(",",$validation->value);
                    if (in_array($value, $validation_value)) {
                        $new_conform = true;
                    }

                    break;
                // multiselect should be null
                case 'multi_select':
                    $value = explode(",",$value);
                    if (in_array($validation->value, $value)) {
                        $new_conform = true;
                    }
                    break;

                case 'temp':
                    $max = $validation->value_max ?? null;
                    $min = $validation->value_min ?? null;

                    if ($value != null) {
                        $value = floatval($value);

                        // for check equal data
                        if ($validation->check_equal == 1) {
                            // for between pH
                            if (!is_null($min) and !is_null($max)) {
                                if ($value <= $max || $value >= $min) {
                                    $new_conform = true;
                                }
                                // validate less than
                            } elseif (is_null($min) and !is_null($max)) {
                                if ($value <= $max) {
                                    $new_conform = true;
                                }
                                // validate greater than
                            } elseif (!is_null($min) and is_null($max)) {
                                if ($value >= $min) {
                                    $new_conform = true;
                                }
                            }
                        // for check non equal data
                        } else {

                            // for between pH
                            if (!is_null($min) and !is_null($max)) {
                                if ($value < $max || $value > $min) {
                                    $new_conform = true;
                                }
                                // validate less than
                            } elseif (is_null($min) and !is_null($max)) {
                                if ($value < $max) {
                                    $new_conform = true;
                                }
                                // validate greater than
                            } elseif (!is_null($min) and is_null($max)) {
                                if ($value > $min) {
                                    $new_conform = true;
                                }
                            }

                        }
                    } else {
                        $new_conform = false;
                    }
                    break;

                default:
                    $new_conform = false;
                    break;
            }

            $check_condition = $this->equipmentRepository->checkNonComform($data_conformity);

            // if need create non conformity
            if ($new_conform) {

                // if this non conformity not exist
                if (!$check_condition) {
                    // create new
                    $hazard_id = $this->createTempHazard($equipment, $validation->hazard_name, $validation->hazard_type);
                    $data_conformity['hazard_id'] =  $hazard_id;
                    $non_conform = $this->equipmentRepository->updateOrCreateNonComform($data_conformity);

                // if this non conformity already exist
                } else {
                    // turn on the existed
                    $this->equipmentRepository->revertNonconformity($check_condition);
                }

            // if no need create non conformity
            } else {
                // turn off the existed
                $this->equipmentRepository->removeNonconformity($check_condition);
            }
        }

        return true;
    }

    public function createTempHazard($equipment , $name , $type) {
        $data_hazard = [
            'name' => $name,
            'property_id' => $equipment->property_id,
            'assess_id' => $equipment->assess_id,
            'area_id' => $equipment->area_id,
            'location_id' => $equipment->location_id,
            'decommissioned' => 0,
            'total_risk' => 0,
            'type' => $type,
            'is_temp' => 1
        ];
        $hazard = $this->hazardRepository->create($data_hazard);
        if (isset($equipment->assessment->classification)) {
            if ($equipment->assessment->classification == ASSESSMENT_FIRE_TYPE) {
                $hazard->reference = 'FH'.$hazard->id;
                $hazard->assess_type = ASSESSMENT_FIRE_TYPE;
            } elseif ($equipment->assessment->classification == ASSESSMENT_WATER_TYPE) {
                $hazard->reference = 'WH'.$hazard->id;
                $hazard->assess_type = ASSESSMENT_WATER_TYPE;
            } elseif ($equipment->assessment->classification == ASSESSMENT_HS_TYPE) {
                $hazard->reference = 'HSH'.$hazard->id;
                $hazard->assess_type = ASSESSMENT_HS_TYPE;
            }
        } else {
            $hazard->reference = 'HZ'.$hazard->id;
        }

        $hazard->record_id = $hazard->id;
        $hazard->save();
        $comment = "Created Hazard by created Nonconformity in equipment {$equipment->reference}";
        \ComplianceHelpers::logAudit(HAZARD_TYPE, $hazard->id, AUDIT_ACTION_ADD, $hazard->reference, $equipment->id, $comment);
        return $hazard->id;
    }

    public function listNaEquipments($property_id, $relations = []){
        return $this->equipmentRepository->listNaEquipments($property_id, $relations);
    }

    function floattostr( $val )
    {
        preg_match( "#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($val), $o );
        return $o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');
    }

    public function getEquipmentPaginate($property_id, $assess_id, $decommissioned) {
        return $this->equipmentRepository->getEquipmentPaginate($property_id, $assess_id, $decommissioned);
    }

    public function getEquipmentDetails($id, $relations = []) {
        return $this->equipmentRepository->getEquipmentDetails($id, $relations);
    }

    public function getHazardRegisterSummary($equipment, $assess_type)
    {
        $hazards = new Collection();
        if (count($equipment->nonconformities)) {
            foreach ($equipment->nonconformities as $nonconformities) {
                if ($nonconformities->hazard) {
                    $hazards->push($nonconformities->hazard);
                }
            }
            //hazard is created in the register will have is_temp = 1 and assess_type is null
            $register_data = $hazards->where('assess_id', 0)
                ->where('is_deleted', 0)
//                ->where('is_temp', 0)
//                ->where('assess_type', $assess_type)
                ->where('decommissioned', 0)
                ->all();
            $decommissioned_register_data = $hazards->where('assess_id', 0)
                ->where('is_deleted', 0)
//                ->where('is_temp', 0)
//                ->where('assess_type', $assess_type)
                ->where('decommissioned', 1)
                ->all();
            return ['register_data' => $register_data, 'decommissioned_register_data' => $decommissioned_register_data];
        }
    }

    public function getTemperatureAndPhOnly($active){
        if(isset($active) && isset($active['active_field']) && count($active['active_field'])){
            $fillable = $this->tempLogRepository->getFillable();
            $new_active_keys = array_intersect($fillable, $active['active_field']);
            $active['active_field'] = $new_active_keys;
        }
        return $active;
    }

    public function getHistoryTemperature($id, $key){
        $result = [];
        $temp_log = $this->tempLogRepository->where('equipment_id', $id)->whereRaw("$key IS NOT NULL")->get();
        if(count($temp_log)){
            foreach ($temp_log as $k => $temp){
                $result[$k][] = $temp->created_at->format('d/m/Y H:i');
                //pH
                $extend = ' °C';
                if($key == 'ph'){
                    $extend = ' pH';
                }
                $result[$k][] = $temp->{$key} . $extend;
            }
        }
        return $result;
    }

}
