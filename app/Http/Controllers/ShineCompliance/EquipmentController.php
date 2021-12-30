<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Services\ShineCompliance\ChartService;
use App\Services\ShineCompliance\EquipmentService;
use App\Services\ShineCompliance\AreaService;
use App\Services\ShineCompliance\LocationService;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\SystemService;
use App\Services\ShineCompliance\ZoneService;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Http\Request\ShineCompliance\Equipment\EquipmentRequest;

class EquipmentController extends Controller
{
    private $equipmentService;
    private $assessmentService;
    private $areaService;
    private $locationService;
    private $propertyService;
    private $systemService;
    private $chartService;
    private $zoneService;

    public function __construct(EquipmentService $equipmentService, AssessmentService $assessmentService, SystemService $systemService,
                                LocationService $locationService, AreaService $areaService, PropertyService $propertyService,
                                ChartService $chartService,
                                ZoneService $zoneService)
    {
        $this->equipmentService = $equipmentService;
        $this->assessmentService = $assessmentService;
        $this->areaService = $areaService;
        $this->locationService = $locationService;
        $this->propertyService = $propertyService;
        $this->systemService = $systemService;
        $this->chartService = $chartService;
        $this->zoneService = $zoneService;
    }

    public function detail(Request $request) {

        $equipment = $this->equipmentService->getEquipment($request->id);

        if ($request->has('page')) {
            $category = $this->equipmentService->getEquipmentPaginate($equipment->property_id, $equipment->assess_id, $equipment->decommissioned);
            $category =  \ComplianceHelpers::arrayPaginator($category, $request);
        } else {
            $category = [];
        }
        //log audit
        $comment = \Auth::user()->full_name . " viewed equipment detail";
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_VIEW, $equipment->reference, $equipment->property_id, $comment);

        return view('shineCompliance.equipments.tab_equipment',['equipment' => $equipment, 'category' => $category]);

    }

    public function getAddEquipment($property_id, Request $request) {
        $assessment = null;
        $assess_id = 0;
        $types = $this->equipmentService->getAllTypes();
        $is_water_temperature = false;
        if ($request->has('assess_id')) {
            $assess_id = $request->assess_id;
            $assessment = $this->assessmentService->getAssessmentDetail($assess_id);
            // get types water temperature
            if ($assessment->assess_classification == WATER && $assessment->type == ASSESS_TYPE_WATER_TEMP) {
                $types = $this->equipmentService->getAllTypesWaterTemperature();
                $is_water_temperature = true;
            }
        }

        $system_id = $request->system_id ?? false;
        $system =  null;
        if ($system_id) {
           $system = $this->systemService->getSystem($system_id);
        }


        $property =  $this->propertyService->getProperty($property_id);
        $areas = $this->areaService->getRegisterAssessmentArea($property_id, $assess_id);

        $specificLocations = $this->equipmentService->getEquipmentSpecificLocation();

        $reasons = $this->equipmentService->getEquipmentDropdownData(EQUIPMENT_REASON_INACCESS);
        $operationals = $this->equipmentService->getEquipmentDropdownData(OPERATIONAL_USE);
        $frequencies = $this->equipmentService->getEquipmentDropdownData(FREQUENCY_USE);
        $drain_locations = $this->equipmentService->getEquipmentDropdownData(DRAIN_LOCATION);
        $cold_feed_location = $this->equipmentService->getEquipmentDropdownData(COLD_FIELD_LOCATION);
        $outlet_feed_location = $this->equipmentService->getEquipmentDropdownData(OUTLET_FIELD_LOCATION);
        $direct_fired = $this->equipmentService->getEquipmentDropdownData(DIRECT_INDIRECT_FIRED);
        $horizontal_vertical = $this->equipmentService->getEquipmentDropdownData(HORIZONTAL_VERTICAL);
        $insulation_type = $this->equipmentService->getEquipmentDropdownData(INSULATION_TYPE);
        $cleanliness = $this->equipmentService->getEquipmentDropdownData(CLEANLINESS);
        $ease_cleaning = $this->equipmentService->getEquipmentDropdownData(EASE_OF_CLEANING);
        $labelling = $this->equipmentService->getEquipmentDropdownData(LABELLING);
        $sources = $this->equipmentService->getEquipmentDropdownData(SOURCE);
        $source_accesses = $this->equipmentService->getEquipmentDropdownData(SOURCE_ACCESSIBILITY);
        $source_conditions = $this->equipmentService->getEquipmentDropdownData(SOURCE_CONDITION);
        $insulation_conditions = $this->equipmentService->getEquipmentDropdownData(INSULATION_CONDITION);
        $pipe_insulation_conditions = $this->equipmentService->getEquipmentDropdownData(PIPE_INSULATION_CONDITION);
        $operation_exposure = $this->equipmentService->getEquipmentDropdownData(OPERATION_EXPOSURE);
        $degree_of_fouling = $this->equipmentService->getEquipmentDropdownData(DEGREE_OF_FOULING);
        $degree_of_bios = $this->equipmentService->getEquipmentDropdownData(DEGREE_OF_BIO);
        $extent_corrosion = $this->equipmentService->getEquipmentDropdownData(EXTENT_CORROSION);
        $construction_materials = $this->equipmentService->getEquipmentDropdownData(CONSTRUCTION_MATERIAL);
        $material_of_pipework = $this->equipmentService->getEquipmentDropdownData(MATERIAL_OF_PIPEWORK);
        $aerosol_risks = $this->equipmentService->getEquipmentDropdownData(AEROSOL_RISK);
        $evidence_stages = $this->equipmentService->getEquipmentDropdownData(EVIDENCE_STAGE);
        $pipe_insulations = $this->equipmentService->getEquipmentDropdownData(PIPE_INSULATION);
        $nearest_furthest = $this->equipmentService->getEquipmentDropdownData(NEARSEST_FURTHEST);

        //log audit
        $comment = \Auth::user()->full_name . " viewed add equipment form for property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.equipments.add_equipment',[
            'types' => $types,
            'reasons' => $reasons,
            'property' => $property,
            'property_id' => $property_id,
            'system' => $system,
            'specificLocations' => $specificLocations,
            'operationals' => $operationals,
            'frequencies' => $frequencies,
            'drain_locations' => $drain_locations,
            'cold_feed_location' => $cold_feed_location,
            'outlet_feed_location' => $outlet_feed_location,
            'direct_fired' => $direct_fired,
            'horizontal_vertical' => $horizontal_vertical,
            'insulation_type' => $insulation_type,
            'cleanliness' => $cleanliness,
            'labelling' => $labelling,
            'ease_cleaning' => $ease_cleaning,
            'sources' => $sources,
            'source_accesses' => $source_accesses,
            'source_conditions' => $source_conditions,
            'insulation_conditions' => $insulation_conditions,
            'pipe_insulations' => $pipe_insulations,
            'operation_exposure' => $operation_exposure,
            'degree_of_fouling' => $degree_of_fouling,
            'degree_of_bios' => $degree_of_bios,
            'extent_corrosion' => $extent_corrosion,
            'aerosol_risks' => $aerosol_risks,
            'assessment' => $assessment,
            'construction_materials' => $construction_materials,
            'material_of_pipework' => $material_of_pipework,
            'pipe_insulation_conditions' => $pipe_insulation_conditions,
            'evidence_stages' => $evidence_stages,
            'assess_id' => $assess_id,
            'nearest_furthest' => $nearest_furthest,
            'areas' => $areas,
            'is_water_temperature' => $is_water_temperature,
        ]);
    }

    public function postAddEquipment($assess_id, EquipmentRequest $request) {
        $data = $request->validated();
        $equipment = $this->equipmentService->updateOrCreateEquipment($data);

        if (isset($equipment)) {
            if ($equipment['status_code'] == STATUS_OK) {
                if (isset($equipment['data']->assess_id) and $equipment['data']->assess_id == 0) {
                    return redirect()->route('shineCompliance.register_equipment.detail',[ 'id' => $equipment['data']->id ?? 0])->with('msg', $equipment['msg']);
                } else {
                    return redirect()->route('shineCompliance.equipment.detail',[ 'id' => $equipment['data']->id ?? 0])->with('msg', $equipment['msg']);
                }
            } else {
                return redirect()->back()->with('err', $equipment['msg']);
            }
        }
    }

    public function getEditEquipment($id) {

        $equipment = $this->equipmentService->getEquipment($id);
        $types = $this->equipmentService->getAllTypes();
        $specificLocations = $this->equipmentService->getEquipmentSpecificLocation();

        $reasons = $this->equipmentService->getEquipmentDropdownData(EQUIPMENT_REASON_INACCESS);
        $operationals = $this->equipmentService->getEquipmentDropdownData(OPERATIONAL_USE);
        $frequencies = $this->equipmentService->getEquipmentDropdownData(FREQUENCY_USE);
        $drain_locations = $this->equipmentService->getEquipmentDropdownData(DRAIN_LOCATION);
        $cold_feed_location = $this->equipmentService->getEquipmentDropdownData(COLD_FIELD_LOCATION);
        $outlet_feed_location = $this->equipmentService->getEquipmentDropdownData(OUTLET_FIELD_LOCATION);
        $direct_fired = $this->equipmentService->getEquipmentDropdownData(DIRECT_INDIRECT_FIRED);
        $horizontal_vertical = $this->equipmentService->getEquipmentDropdownData(HORIZONTAL_VERTICAL);
        $insulation_type = $this->equipmentService->getEquipmentDropdownData(INSULATION_TYPE);
        $cleanliness = $this->equipmentService->getEquipmentDropdownData(CLEANLINESS);
        $ease_cleaning = $this->equipmentService->getEquipmentDropdownData(EASE_OF_CLEANING);
        $labelling = $this->equipmentService->getEquipmentDropdownData(LABELLING);
        $sources = $this->equipmentService->getEquipmentDropdownData(SOURCE);
        $source_accesses = $this->equipmentService->getEquipmentDropdownData(SOURCE_ACCESSIBILITY);
        $source_conditions = $this->equipmentService->getEquipmentDropdownData(SOURCE_CONDITION);
        $insulation_conditions = $this->equipmentService->getEquipmentDropdownData(INSULATION_CONDITION);
        $pipe_insulations = $this->equipmentService->getEquipmentDropdownData(PIPE_INSULATION);
        $pipe_insulation_conditions = $this->equipmentService->getEquipmentDropdownData(PIPE_INSULATION_CONDITION);
        $operation_exposure = $this->equipmentService->getEquipmentDropdownData(OPERATION_EXPOSURE);
        $degree_of_fouling = $this->equipmentService->getEquipmentDropdownData(DEGREE_OF_FOULING);
        $degree_of_bios = $this->equipmentService->getEquipmentDropdownData(DEGREE_OF_BIO);
        $extent_corrosion = $this->equipmentService->getEquipmentDropdownData(EXTENT_CORROSION);
        $construction_materials = $this->equipmentService->getEquipmentDropdownData(CONSTRUCTION_MATERIAL);
        $material_of_pipework = $this->equipmentService->getEquipmentDropdownData(MATERIAL_OF_PIPEWORK);
        $aerosol_risks = $this->equipmentService->getEquipmentDropdownData(AEROSOL_RISK);
        $evidence_stages = $this->equipmentService->getEquipmentDropdownData(EVIDENCE_STAGE);
        $nearest_furthest = $this->equipmentService->getEquipmentDropdownData(NEARSEST_FURTHEST);
        $areas = $this->areaService->getRegisterAssessmentArea($equipment->property_id, $equipment->assess_id);
        $locations = $this->assessmentService->getLocationsByAssessmentAndArea($equipment->assess_id, $equipment->area_id);
        $selectedSpecificLocations = $this->equipmentService->getSpecificlocationValue($id);

        $is_water_temperature = false;
        if ($assess_id = $equipment->assess_id) {
            $assessment = $this->assessmentService->getAssessmentDetail($assess_id);
            // get types water temperature
            if ($assessment->assess_classification == WATER && $assessment->type == ASSESS_TYPE_WATER_TEMP) {
                $types = $this->equipmentService->getAllTypesWaterTemperature();
                $is_water_temperature = true;
            }
        }
        //log audit
        $comment = \Auth::user()->full_name . " viewed equipment to edit";
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_VIEW, $equipment->reference, $equipment->property_id, $comment);

        return view('shineCompliance.equipments.edit_equipment',[
            'assess_id' => $equipment->assess_id,
            'types' => $types,
            'reasons' => $reasons,
            'specificLocations' => $specificLocations,
            'pipe_insulations' => $pipe_insulations,
            'operationals' => $operationals,
            'frequencies' => $frequencies,
            'drain_locations' => $drain_locations,
            'cold_feed_location' => $cold_feed_location,
            'outlet_feed_location' => $outlet_feed_location,
            'direct_fired' => $direct_fired,
            'horizontal_vertical' => $horizontal_vertical,
            'insulation_type' => $insulation_type,
            'cleanliness' => $cleanliness,
            'labelling' => $labelling,
            'ease_cleaning' => $ease_cleaning,
            'sources' => $sources,
            'source_accesses' => $source_accesses,
            'source_conditions' => $source_conditions,
            'insulation_conditions' => $insulation_conditions,
            'operation_exposure' => $operation_exposure,
            'degree_of_fouling' => $degree_of_fouling,
            'degree_of_bios' => $degree_of_bios,
            'extent_corrosion' => $extent_corrosion,
            'aerosol_risks' => $aerosol_risks,
            'assessment' => $equipment->assessment,
            'equipment' => $equipment,
            'areas' => $areas,
            'locations' => $locations,
            'construction_materials' => $construction_materials,
            'material_of_pipework' => $material_of_pipework,
            'pipe_insulation_conditions' => $pipe_insulation_conditions,
            'evidence_stages' => $evidence_stages,
            'nearest_furthest' => $nearest_furthest,
            'selectedSpecificLocations' => $selectedSpecificLocations,
            'is_water_temperature' => $is_water_temperature,
        ]);
    }

    public function postEditEquipment($id, EquipmentRequest $request) {
        $data = $request->validated();

        $equipment = $this->equipmentService->updateOrCreateEquipment($data, $id);

        if (isset($equipment)) {
            if ($equipment['status_code'] == STATUS_OK) {
                if (isset($equipment['data']->assess_id) and $equipment['data']->assess_id == 0) {
                    return redirect()->route('shineCompliance.register_equipment.detail',[ 'id' => $id])->with('msg', $equipment['msg']);
                } else {
                    return redirect()->route('shineCompliance.equipment.detail',[ 'id' => $id])->with('msg', $equipment['msg']);
                }
            } else {
                return redirect()->back()->with('err', $equipment['msg']);
            }
        }
    }

    public function getSpecificDropdown(Request $request) {

        $parent_id = ($request->has('parent_id')) ? $request->parent_id : 0;

        $dropdowns = $this->equipmentService->getEquipmentSpecificLocation($parent_id);
        $have_child = false;
        foreach ($dropdowns as $dropdown) {
            if (count($dropdown->allChildrens)) {
                $have_child = true;
            }
        }

        $response = [
            'data' => $dropdowns,
            'have_child' => $have_child
        ];
        return response()->json($response);
    }

    public function getActiveSection(Request $request) {
        $type = $request->type ?? 0;
        $is_water_temperature = $request->is_water_temperature ?? false;
        $data = $this->equipmentService->getActiveSection($type, $is_water_temperature);
        if (!is_null($data)) {
            return response()->json(['status' => 200 ,
             'data' => $data['active_field'] ?? [],
             'validation' => $data['validation'] ?? [],
             'required' => $data['required'] ?? [],
             'template_id' => $data['template_id'] ?? 0]);
        } else {
             return response()->json(['status' => 404 ,'data' => [], 'template_id' => 0]);
        }
    }

    public function searchEquipment(Request $request) {
        $assess_id = $request->assess_id ?? 0;
        $property_id = $request->property_id ?? 0;
        $query_string = '';
        $templates = [];
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        if ($request->has('templates')) {
            $templates = $request->templates;
        }
        $data = $this->equipmentService->searchEquipment($query_string,$property_id, $assess_id, $templates);
        return response()->json($data);
    }

    public function searchEquipmentType(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }

        if ($request->has('assessment_classification') && $request->has('assessment_type')) {
            $assessment_classification = $request->assessment_classification;
            $assessment_type = $request->assessment_type;
            // get types water temperature
            if ($assessment_classification == WATER && $assessment_type == ASSESS_TYPE_WATER_TEMP) {
                $data = $this->equipmentService->searchEquipmentTypeWaterTemperature($query_string);
            }
        }
        $data = $this->equipmentService->searchEquipmentType($query_string);
        if ($request->has('assessment_classification') && $request->has('assessment_type')) {
            $assessment_classification = $request->assessment_classification;
            $assessment_type = $request->assessment_type;

            // get types water temperature
            if ($assessment_classification == WATER && $assessment_type == ASSESS_TYPE_WATER_TEMP) {
                $data = $this->equipmentService->searchEquipmentTypeWaterTemperature($query_string);
            }
        }
        return response()->json($data);
    }

    public function registerEquipmentDetail(Request $request) {
        $equipment = $this->equipmentService->getEquipment($request->id);
        $can_update = true;

        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
            if (\CommonHelpers::isClientUser() && $equipment->property->client_id == \Auth::user()->client_id) {
                $can_update = true;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $equipment->property_id)) {
                $can_update = false;
            }
        }

        if ($equipment->is_locked) {
            $can_update = false;
        }

        if ($request->has('page')) {
            $category = $this->equipmentService->getEquipmentPaginate($equipment->property_id, $equipment->assess_id, $equipment->decommissioned);
            $category =  \ComplianceHelpers::arrayPaginator($category, $request);
        } else {
            $category = [];
        }

        $comment = \Auth::user()->full_name . " viewed equipment detail ". $equipment->name ." on system". ($equipment->system->name ?? ''). "  on property " . ($equipment->property->name ?? '');
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_VIEW, $equipment->reference, $equipment->property_id, $comment);
        $width_label = 5;
        return view('shineCompliance.equipments.register_equipment_detail',['can_update' => $can_update, 'equipment' => $equipment, 'category' => $category, 'width_label' => $width_label]);
    }

    public function propertyEquipment($property_id) {
        $equipments =  $this->equipmentService->getAllEquipments(9, $property_id, null);
        $property =  $this->propertyService->getProperty($property_id);
        $can_add_new = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_new = false;
            if (\CommonHelpers::isClientUser() && $property->client_id == \Auth::user()->client_id) {
                $can_add_new = true;
            }
        } elseif(\CommonHelpers::isSystemClient()){
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id)) {
                $can_add_new = false;
            }
        }

        $comment = \Auth::user()->full_name . " viewed equipment list on property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.properties.equipment',['can_add_new' => $can_add_new, 'equipments' => $equipments, 'property' => $property, 'property_id' => $property_id]);
    }

    public function getPhotographyDetails($id){
        $equipment = $this->equipmentService->getEquipmentDetails($id, ['property.zone', 'system']);
        if(!$equipment){
            abort(404);
        }
        $can_update = true;

        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
            if (\CommonHelpers::isClientUser() && $equipment->property->client_id == \Auth::user()->client_id) {
                $can_update = true;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $equipment->property_id)) {
                $can_update = false;
            }
        }

        if ($equipment->is_locked) {
            $can_update = false;
        }
        $can_update = false;

        $comment = \Auth::user()->full_name . " viewed photography equipment ". $equipment->name ." on system". ($equipment->system->name ?? ''). "  on property " . ($equipment->property->name ?? '');
        \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_VIEW, $equipment->reference, $equipment->property_id, $comment);
        return view('shineCompliance.equipments.photography_equipment_detail',[ 'equipment' => $equipment, 'property' => $equipment->property, 'property_id' => $equipment->property->id, 'can_update' => $can_update]);
    }

    public function registerOverall($id, Request $request) {
        $equipment = $this->equipmentService->getEquipmentDetails($id, ['nonconformities.hazard','property.zone', 'system']);
        if(!$equipment){
            abort(404);
        }
        $property = $equipment->property;
        $type = FIRE;
        $data = $this->equipmentService->getHazardRegisterSummary($equipment,ASSESSMENT_FIRE_TYPE);
        $register_data = $data['register_data'];
        $decommission_register_data = $data['decommissioned_register_data'];
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
            if (\CommonHelpers::isClientUser() && $equipment->property->client_id == \Auth::user()->client_id) {
                $can_update = true;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $equipment->property_id)) {
                $can_update = false;
            }
        }

        if ($equipment->is_locked) {
            $can_update = false;
        }
        $can_update = false;
        return view('shineCompliance.equipments.hazard_summary', ['property_id' => $property->id,
            'property' => $property,
            'can_update' => $can_update,
            'register_data' => $register_data,
            'decommission_register_data' => $decommission_register_data,
            'type' => $type,
            'equipment' => $equipment,
        ]);
    }

    public function getNonconformity($id){
        $equipment = $this->equipmentService->getEquipmentDetails($id, ['nonconformities','property.zone', 'system']);
        if(!$equipment){
            abort(404);
        }
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
            if (\CommonHelpers::isClientUser() && $equipment->property->client_id == \Auth::user()->client_id) {
                $can_update = true;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $equipment->property_id)) {
                $can_update = false;
            }
        }

        if ($equipment->is_locked) {
            $can_update = false;
        }
        $can_update = false;
        return view('shineCompliance.equipments.nonconformity', [
            'property_id' => $equipment->property->id,
            'property' => $equipment->property,
            'nonconformities' => $equipment->nonconformities,
            'can_update' => $can_update,
            'equipment' => $equipment,
        ]);
    }

    public function getPreplannedMaintenance($id){
        $equipment = $this->equipmentService->getEquipmentDetails($id, ['property.zone', 'system.programmes.documentInspection']);
        if(!$equipment){
            abort(404);
        }
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
            if (\CommonHelpers::isClientUser() && $equipment->property->client_id == \Auth::user()->client_id) {
                $can_update = true;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $equipment->property_id)) {
                $can_update = false;
            }
        }

        if ($equipment->is_locked) {
            $can_update = false;
        }
        $can_update = false;
        $data_reinspection_chart = $this->chartService->createProgrammeReinspectionChart(NULL, $equipment->system_id);
        return view('shineCompliance.equipments.programmes', [
            'property_id' => $equipment->property->id,
            'property' => $equipment->property,
            'programmes' => $equipment->system->programmes ?? [],
            'can_update' => $can_update,
            'equipment' => $equipment,
            'data_reinspection_chart' => $data_reinspection_chart,
        ]);
    }

    public function getTemperature($id){
        $equipment = $this->equipmentService->getEquipmentDetails($id, ['property.zone', 'system', 'tempLog']);
        if(!$equipment){
            abort(404);
        }
        $can_update = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_update = false;
            if (\CommonHelpers::isClientUser() && $equipment->property->client_id == \Auth::user()->client_id) {
                $can_update = true;
            }
        } else {
            // check update permission
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_EQUIPMENT) || !\CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $equipment->property_id)) {
                $can_update = false;
            }
        }

        if ($equipment->is_locked) {
            $can_update = false;
        }
        $can_update = false;
        // active fields
        // ['return_temp','flow_temp','inlet_temp','stored_temp','top_temp','bottom_temp','flow_temp_gauge_value','return_temp_gauge_value','ambient_area_temp','incoming_main_pipe_work_temp','ph']
        $active = $this->equipmentService->getActiveSection($equipment->type, true);
        $active = $this->equipmentService->getTemperatureAndPhOnly($active);
        $data_temperature_chart = $this->chartService->createTemperatureChart(NULL, $id);
        return view('shineCompliance.equipments.temperature', [
            'property_id' => $equipment->property->id,
            'property' => $equipment->property,
            'temperature' => $equipment->tempLog,
            'can_update' => $can_update,
            'equipment' => $equipment,
            'active' => $active,
            'data_temperature_chart' => $data_temperature_chart
        ]);
    }

    public function getHistoryTemperature(Request $request){
        $id = $request->id;
        $key = $request->key;
        $result = [];
        if($id && $key){
            $result = $this->equipmentService->getHistoryTemperature($id, $key);
        }
        return ['data'=>$result];
    }

    public function postUpdateTemperature(Request $request){
        $equipment = $this->equipmentService->getEquipmentDetails($request->equipment_id);
        $equipment = $this->equipmentService->updateTemperature($request, $equipment);

        if (isset($equipment)) {
            if ($equipment['status_code'] == STATUS_OK) {
                if (isset($equipment['data']->assess_id) and $equipment['data']->assess_id == 0) {
                    return redirect()->route('shineCompliance.equipment.temperature_ph',[ 'id' => $request->equipment_id])->with('msg', $equipment['msg']);
                }
            } else {
                return redirect()->back()->with('err', $equipment['msg']);
            }
        }
        return redirect()->route('shineCompliance.equipment.temperature_ph',[ 'id' => $request->equipment_id])->with('msg', $equipment['msg']);
    }
}
