<?php


namespace App\Http\Controllers\API\V2\Compliance\Assessment;


use App\Helpers\CommonHelpers;
use App\Http\Controllers\API\AppBaseController;
use App\Http\Request\API\ShineCompliance\ApiUploadImageRequest;
use App\Services\ShineCompliance\AreaService;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\AssessmentUploadService;
use App\Services\ShineCompliance\EquipmentService;
use App\Services\ShineCompliance\HazardService;
use App\Services\ShineCompliance\LocationService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\SystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiAssessmentController extends AppBaseController
{
    private $propertyService;
    private $assessmentService;
    private $equipmentService;
    private $areaService;
    private $locationService;
    private $uploadService;
    private $systemService;
    private $hazardService;

    public function __construct(PropertyService $propertyService,
                                AssessmentService $assessmentService,
                                AssessmentUploadService $uploadService,
                                EquipmentService $equipmentService,
                                HazardService $hazardService,
                                AreaService $areaService,
                                SystemService $systemService,
                                LocationService $locationService)
    {
        $this->propertyService = $propertyService;
        $this->assessmentService = $assessmentService;
        $this->equipmentService = $equipmentService;
        $this->areaService = $areaService;
        $this->locationService = $locationService;
        $this->uploadService = $uploadService;
        $this->systemService = $systemService;
        $this->hazardService = $hazardService;
    }

    public function getAllDropdowns()
    {
        $result = [
            'compliance_system_classifications' => $this->systemService->getAllSystemClassification(),
            'compliance_system_types' => $this->systemService->getAllSystemType(),
            'cp_assessment_answers' => $this->assessmentService->getAllAssessmentAnswers(),
            'cp_assessment_questions' => $this->assessmentService->getAllAssessmentQuestions(),
            'cp_assessment_statement_answers' => $this->assessmentService->getAllAssessmentStatementAnswers(),
            'cp_assessment_sections' => $this->assessmentService->getAllAssessmentSections(),
            'cp_equipment_dropdown' => $this->equipmentService->getAllEquipmentDropdowns(),
            'cp_equipment_dropdown_data' => $this->equipmentService->getAllEquipmentDropdownData(),
            'cp_equipment_sections' => $this->equipmentService->getAllEquipmentSections(),
            'cp_equipment_specific_location' => $this->equipmentService->getAllEquipmentSpecificLocations(),
            'cp_equipment_template_sections' => $this->equipmentService->getAllEquipmentTemplateSections(),
            'cp_equipment_templates' => $this->equipmentService->getAllEquipmentTemplates(),
            'cp_equipment_types' => $this->equipmentService->getAllTypes(),
            'cp_temp_validation' => $this->equipmentService->getAllTempValidation(),
            'cp_validate_nonconformities' => $this->equipmentService->getValidateNonconformities(),
            'cp_hazard_action_recommendation_noun' => $this->hazardService->getHazardActionRecommendationNoun(),
            'cp_hazard_action_recommendation_verb' => $this->hazardService->getHazardActionRecommendationVerb(),
            'cp_hazard_action_responsibilities' => $this->hazardService->getHazardActionResponsibilities(),
            'cp_hazard_likelihood_harm' => $this->hazardService->getHazardLikelihoodHarm(),
            'cp_hazard_measurement' => $this->hazardService->getHazardMeasurement(),
            'cp_hazard_potential' => $this->hazardService->getHazardPotential(),
            'cp_hazard_specific_location' => $this->hazardService->getAllHazardSpecificLocation(),
            'cp_hazard_type' => $this->hazardService->getHazardType(),
            'cp_hazard_inaccessible_reason' => $this->hazardService->getInaccessibleReasonHazard(),
            'tbl_property_info_dropdowns' => $this->propertyService->getAllPropertyInfo(),
            'tbl_property_info_dropdown_data' => $this->propertyService->getAllPropertyInfoDropdownData(),
            'tbl_vulnerable_occupant_types' => $this->propertyService->getVulnerableTypes(),
            'programme_type' => $this->propertyService->getAllProgrammeType(),
            'asset_class' => $this->propertyService->getAllAssetClass(true),
            'asset_type' => $this->propertyService->getAllAssetClass(false),
            'primary_use' => $this->propertyService->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID),
            'construction_materials' => $this->propertyService->getAllConstructionMaterials(), //update to all domain
            'property_dropdown' => $this->propertyService->getPropertyDropdownTitles(),
            'property_dropdown_data' => $this->propertyService->getAllPropertyDropdownData(),
            'decommissioned_reasons' => $this->assessmentService->getDecommissionedReasons(),
            'fire_safety_answers' => $this->assessmentService->getAllFireSafetyAnswers(),
            'management_info_questions' => $this->assessmentService->getAllManagementQuestions(),
            'management_info_answers' => $this->assessmentService->getAllManagementAnswers(),
            'other_info_questions' => $this->assessmentService->getAllOtherQuestions(),
            'other_info_answers' => $this->assessmentService->getAllOtherAnswers(),
            'cp_assessment_aborted_reason' => $this->assessmentService->getAssessmentAbortedReason(),
        ];

        return $this->sendResponse($result, 'Get Dropdown Successfully!');
    }

    public function listAssessment()
    {
        $assessments = $this->assessmentService->getSentOutAssessments();

        return $this->sendResponse($assessments, 'Get Assessments Successfully!');
    }

    public function getAssessmentDetail($assess_id)
    {

        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id, [
            'assessmentInfo',
            'unDecommissionHazard',
            'equipments',
            'systems',
            'fireExits',
            'assemblyPoints',
            'vehicleParking',
        ])) {
            return $this->sendError('Not found Assessment');
        }
        if ($assessment->status != ASSESSMENT_STATUS_LOCKED) {
            return $this->sendError('Not found Assessment');
        }

        // Property address
        $propertyAddress = '';
        $propertyAddress .= (isset($assessment->property) && isset($assessment->property->propertyInfo) && $assessment->property->propertyInfo->street_number)
                                        ? ($assessment->property->propertyInfo->street_number . ', ') : '';
        $propertyAddress .= (isset($assessment->property) && isset($assessment->property->propertyInfo) && $assessment->property->propertyInfo->town)
                                        ? ($assessment->property->propertyInfo->town . ', ') : '';
        $propertyAddress .= (isset($assessment->property) && isset($assessment->property->propertyInfo) && $assessment->property->propertyInfo->address5)
                                        ? ($assessment->property->propertyInfo->address5) : '';
        $propertyAddress = rtrim($propertyAddress, ', ');

        $result = [
            'id' => $assessment->id,
            'property_id' => $assessment->property_id,
            'property_reference' => $assessment->property->reference ?? null,
            'property_name' => $assessment->property->name ?? null,
            'property_address' => $propertyAddress,
            'property_postcode' => $assessment->property->propertyInfo->postcode ?? null,
            'client_id' => $assessment->client_id,
            'classification' => $assessment->classification,
            'type' => $assessment->type,
            'reference' => $assessment->reference,
            'assess_start_date' => $assessment->assess_start_date ?? null,
            'assess_start_time' => $assessment->assess_start_time ?? null,
            'sent_out_date' => $assessment->sent_out_date,
            'fire_safety' => $assessment->assessmentInfo->fire_safety,
            'fire_safety_other' => $assessment->assessmentInfo->fire_safety_other,
            // Relations
            'assessment_info' => [
                'setting_non_conformities' => $assessment->assessmentInfo->setting_non_conformities ?? null,
                'setting_property_size_volume' => $assessment->assessmentInfo->setting_property_size_volume ?? null,
                'setting_vehicle_parking' => $assessment->assessmentInfo->setting_show_vehicle_parking ?? null,
                'setting_fire_safety' => $assessment->assessmentInfo->setting_fire_safety ?? null,
                'setting_equipment_details' => $assessment->assessmentInfo->setting_equipment_details ?? null,
                'setting_hazard_photo_required' => $assessment->assessmentInfo->setting_hazard_photo_required ?? null,
                'setting_assessors_note_required' => $assessment->assessmentInfo->setting_assessors_note_required ?? null,
                'objective_scope'  => $assessment->assessmentInfo->objective_scope ?? null,
                'executive_summary'  => $assessment->assessmentInfo->executive_summary ?? null,
                'property_information' => json_decode($assessment->assessmentInfo->property_information, false) ?? null,
            ],
            'areas' => $this->areaService->getAssessmentArea($assessment->id, $assessment->property_id),
            'locations' => $this->locationService->getAssessmentLocation($assessment->id, $assessment->property_id ?? 0),
            'hazards' => $assessment->unDecommissionHazard()->with(['hazardSpecificLocationValue' => function($query) {
                $query->select('cp_hazard_specific_location_value.id',
                    'cp_hazard_specific_location_value.hazard_id',
                    'cp_hazard_specific_location_value.parent_id',
                    DB::raw('CONCAT(IF(IFNULL(`sp2`.`parent_id`, 0) <> 0, CONCAT(`sp2`.`parent_id`, \',\'), \'\'), IF(`sp1`.`parent_id` <> 0, CONCAT(`sp1`.`parent_id`, \',\'), \'\'),`cp_hazard_specific_location_value`.`value`) as value'),
                    'cp_hazard_specific_location_value.other',
                    'cp_hazard_specific_location_value.created_at',
                    'cp_hazard_specific_location_value.updated_at')
                    ->join('cp_hazard_specific_location as sp1', function ($join) {
                        $join->where(DB::raw('find_in_set(sp1.id, cp_hazard_specific_location_value.value)'), '<>', "0");
                    })->leftJoin('cp_hazard_specific_location as sp2', function ($join) {
                        $join->on('sp1.parent_id', 'sp2.id');
                    })
                    ->groupBy('cp_hazard_specific_location_value.id');
            }])->get(),
            'systems' => $assessment->systems,
            'management_info_values' => $this->assessmentService->getAllManagementValues($assessment->id),
            'other_info_values' => $this->assessmentService->getAllOtherValues($assessment->id),
            'equipments' => $assessment->equipments()->with([
                'equipmentType',
                'specificLocationValue' => function($query) {
                    $query->select('cp_equipment_specific_location_value.id',
                        'cp_equipment_specific_location_value.equipment_id',
                        'cp_equipment_specific_location_value.parent_id',
                        DB::raw('CONCAT(IF(IFNULL(`sp2`.`parent_id`, 0) <> 0, CONCAT(`sp2`.`parent_id`, \',\'), \'\'), IF(`sp1`.`parent_id` <> 0, CONCAT(`sp1`.`parent_id`, \',\'), \'\'),`cp_equipment_specific_location_value`.`value`) as value'),
                        'cp_equipment_specific_location_value.other',
                        'cp_equipment_specific_location_value.created_at',
                        'cp_equipment_specific_location_value.updated_at')
                        ->join('cp_equipment_specific_location as sp1', function ($join) {
                            $join->where(DB::raw('find_in_set(`sp1`.`id`, `cp_equipment_specific_location_value`.`value`)'), '<>', "0");
                        })->leftJoin('cp_equipment_specific_location as sp2', function ($join) {
                            $join->on('sp1.parent_id', 'sp2.id');
                        })
                        ->groupBy('cp_equipment_specific_location_value.id');
                },
                'equipmentModel',
                'equipmentConstruction',
                'cleaning',
                'tempAndPh' => function($query) {
                    $query->select('id',
                        'equipment_id',
                        'return_temp',
                        'flow_temp',
                        'inlet_temp',
                        'stored_temp',
                        'top_temp',
                        'bottom_temp',
                        'ph',
                        'flow_temp_gauge_value',
                        'ambient_area_temp',
                        'hot_flow_temp',
                        'cold_flow_temp',
                        'pre_tmv_cold_flow_temp',
                        'pre_tmv_hot_flow_temp',
                        'post_tmv_temp',
                        'mixed_temp',
                        'flow_temp_gauge_value as flow_temp_gauge',
                        'return_temp_gauge_value as return_temp_gauge');
                },
            ])->get(),
            'nonconformities' => $assessment->assessmentActiveNonconformities,
            'fire_exits' => $assessment->fireExits,
            'assembly_points' => $assessment->assemblyPoints,
            'vehicle_parking' => $assessment->vehicleParking,
        ];

        return $this->sendResponse($result, 'Get Assessments Successfully!');
    }

    public function uploadManifest(Request $request)
    {
        $validatedData = $request->validate([
            'assess_id' => 'required|integer',
        ]);

        $result = $this->uploadService->createUploadManifest($validatedData['assess_id']);

        if ($result['status_code'] == 200) {

            return $this->sendResponse($result['data'], $result['msg']);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }

    public function uploadImage(ApiUploadImageRequest $request)
    {
        $data = $request->validated();

        $result = $this->uploadService->uploadImage($data);

        if ($result['status_code'] == 200) {

            return $this->sendResponse($result['data'], $result['msg']);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }

    public function uploadData(Request $request)
    {
        $validatedData = $request->validate([
            'manifest_id' => 'required|integer',
            'assess_id' => 'required|integer',
        ]);

        $result = $this->uploadService->uploadData($request->all(), $request->getContent());

        if ($result['status_code'] == 200) {
            return $this->sendResponse($result['data'], $result['msg']);
        } else {
            return $this->sendError($result['msg'], $result['status_code']);
        }
    }
}
