<?php


namespace App\Services\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Http\Controllers\GeneratePDFController;
use App\Models\DecommissionReason;
use App\Models\ShineCompliance\AssessmentAnswer;
use App\Models\ShineCompliance\AssessmentFireSafetyAnswer;
use App\Models\ShineCompliance\AssessmentManagementAnswer;
use App\Models\ShineCompliance\AssessmentManagementQuestion;
use App\Models\ShineCompliance\AssessmentOtherAnswer;
use App\Models\ShineCompliance\AssessmentOtherQuestion;
use App\Models\ShineCompliance\AssessmentQuestion;
use App\Models\ShineCompliance\AssessmentResult;
use App\Models\ShineCompliance\AssessmentSection;
use App\Models\ShineCompliance\AssessmentStatementAnswer;
use App\Models\ShineCompliance\HazardSpecificLocation;
use App\Models\ShineCompliance\PropertySurvey;
use App\Models\ShineCompliance\ShineDocumentStorage;
use App\Repositories\ShineCompliance\AreaRepository;
use App\Repositories\ShineCompliance\AssessmentManagementQuestionRepository;
use App\Repositories\ShineCompliance\AssessmentManagementValueRepository;
use App\Repositories\ShineCompliance\AssessmentOtherQuestionRepository;
use App\Repositories\ShineCompliance\AssessmentOtherValueRepository;
use App\Repositories\ShineCompliance\AssessmentSamplingRepository;
use App\Repositories\ShineCompliance\ComplianceSystemRepository;
use App\Repositories\ShineCompliance\ItemRepository;
use App\Repositories\ShineCompliance\AssemblyPointRepository;
use App\Repositories\ShineCompliance\AssessmentInfoRepository;
use App\Models\ShineCompliance\AssessmentValue;
use App\Models\ShineCompliance\AssessmentAbortedReason;
use App\Repositories\ShineCompliance\AssessmentRepository;
use App\Repositories\ShineCompliance\EquipmentRepository;
use App\Repositories\ShineCompliance\FireExitRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Repositories\ShineCompliance\LocationRepository;
use App\Repositories\ShineCompliance\NonconformityRepository;
use App\Repositories\ShineCompliance\ProjectRepository;
use App\Repositories\ShineCompliance\PropertyRepository;
use App\Repositories\ShineCompliance\PublishedAssessmentRepository;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\AssessmentSectionRepository;
use App\Repositories\ShineCompliance\AssessmentPlanDocumentRepository;
use App\Repositories\ShineCompliance\AssessmentNoteDocumentRepository;
use App\Repositories\ShineCompliance\VehicleParkingRepository;
use App\Repositories\ShineCompliance\PropertyVulnerableOccupantRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \PDF;
use mikehaertl\pdftk\Pdf as PDFTK;
use App\Repositories\ShineCompliance\TempLogRepository;

class AssessmentService
{
    private $assessmentRepository;
    private $assessmentInfoRepository;
    private $userRepository;
    private $areaRepository;
    private $locationRepository;
    private $hazardRepository;
    private $assessmentSectionRepository;
    private $projectRepository;
    private $propertyRepository;
    private $assessmentPlanDocumentRepository;
    private $assessmentNoteDocumentRepository;
    private $itemRepository;
    private $assemblyRepository;
    private $exitRepository;
    private $vehicleParkingRepository;
    private $equipmentRepository;
    private $systemRepository;
    private $nonconformityRepository;
    private $publishedAssessmentRepository;
    private $vulnerableRepository;
    private $assessmentSamplingRepository;
    private $managementQuestionRepository;
    private $managementValueRepository;
    private $otherQuestionRepository;
    private $otherValueRepository;
    private $tempLogRepository;

    private const PRELOAD_VULNERABLE_OCCUPANT_TYPE = 'No vulnerable residents were identified at the time of this assessment, although all residents are considered to be a sleeping risk”. The latest Government guidance states that it proposes to, “Require the responsible person to prepare a personal emergency evacuation plan (PEEP) for every resident in a high-rise residential building who self-identifies to them as unable to self-evacuate (subject to the resident’s voluntary self-identification) and to do so in consultation with them.” Currently, the responsibility for gathering this information sits with the WCC Housing Team.  See Section K.17.';

    public function __construct(AssessmentRepository $assessmentRepository,
                                AssessmentInfoRepository $assessmentInfoRepository,
                                AssessmentSectionRepository $assessmentSectionRepository,
                                AssessmentManagementQuestionRepository $managementQuestionRepository,
                                AssessmentManagementValueRepository $managementValueRepository,
                                AssessmentOtherQuestionRepository $otherQuestionRepository,
                                AssessmentOtherValueRepository $otherValueRepository,
                                AssessmentSamplingRepository $assessmentSamplingRepository,
                                PropertyRepository $propertyRepository,
                                UserRepository $userRepository,
                                AreaRepository $areaRepository,
                                ItemRepository $itemRepository,
                                LocationRepository $locationRepository,
                                HazardRepository $hazardRepository,
                                AssessmentPlanDocumentRepository $assessmentPlanDocumentRepository,
                                AssessmentNoteDocumentRepository $assessmentNoteDocumentRepository,
                                ProjectRepository $projectRepository,
                                AssemblyPointRepository $assemblyRepository,
                                FireExitRepository $exitRepository,
                                VehicleParkingRepository $vehicleParkingRepository,
                                EquipmentRepository $equipmentRepository,
                                ComplianceSystemRepository $systemRepository,
                                NonconformityRepository $nonconformityRepository,
                                PublishedAssessmentRepository $publishedAssessmentRepository,
                                PropertyVulnerableOccupantRepository $vulnerableRepository,
                                TempLogRepository $tempLogRepository
                                )
    {
        $this->assessmentRepository = $assessmentRepository;
        $this->assessmentInfoRepository = $assessmentInfoRepository;
        $this->assessmentSectionRepository = $assessmentSectionRepository;
        $this->userRepository = $userRepository;
        $this->areaRepository = $areaRepository;
        $this->hazardRepository = $hazardRepository;
        $this->locationRepository = $locationRepository;
        $this->projectRepository = $projectRepository;
        $this->propertyRepository = $propertyRepository;
        $this->assessmentNoteDocumentRepository = $assessmentNoteDocumentRepository;
        $this->assessmentPlanDocumentRepository = $assessmentPlanDocumentRepository;
        $this->itemRepository = $itemRepository;
        $this->assemblyRepository = $assemblyRepository;
        $this->exitRepository = $exitRepository;
        $this->vehicleParkingRepository = $vehicleParkingRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->systemRepository = $systemRepository;
        $this->nonconformityRepository = $nonconformityRepository;
        $this->publishedAssessmentRepository = $publishedAssessmentRepository;
        $this->vulnerableRepository = $vulnerableRepository;
        $this->assessmentSamplingRepository = $assessmentSamplingRepository;
        $this->managementQuestionRepository = $managementQuestionRepository;
        $this->managementValueRepository = $managementValueRepository;
        $this->otherQuestionRepository = $otherQuestionRepository;
        $this->otherValueRepository = $otherValueRepository;
        $this->tempLogRepository = $tempLogRepository;
    }

    public function isWinnerSurveyContractor($property_id, $contractor_id, $type) {
        return $this->projectRepository->isWinnerSurveyContractor($property_id, $contractor_id,$type);
    }

    public function getAllFireSafetyAnswers()
    {
        return AssessmentFireSafetyAnswer::all();
    }

    public function getAllManagementQuestions()
    {
        return AssessmentManagementQuestion::all();
    }

    public function getAllManagementAnswers()
    {
        return AssessmentManagementAnswer::all();
    }

    public function getAllManagementValues($assess_id)
    {
        return $this->managementValueRepository->getAllByAssessId($assess_id);
    }

    public function getAllOtherQuestions()
    {
        return AssessmentOtherQuestion::all();
    }

    public function getAllOtherAnswers()
    {
        return AssessmentOtherAnswer::all();
    }

    public function getAssessmentAbortedReason()
    {
        return AssessmentAbortedReason::all();
    }

    public function getAllOtherValues($assess_id)
    {
        return $this->otherValueRepository->getAllByAssessId($assess_id);
    }

    public function getLeadUsers()
    {
        return $this->userRepository
                ->where('client_id', 1)
                ->where('is_locked', 0)
                ->where('assessor_lead', true)
                ->orderBy('first_name')
                ->get();
    }

    public function getAssessors()
    {
        return $this->userRepository
                ->where('client_id', 1)
                ->where('is_locked', 0)
                ->orderBy('first_name')
                ->get();
    }

    public function getSurveyProjects($property_id, $classification = null) {
        if (!empty($classification)) {
            if ($classification == FIRE) {
                $type = FIRE_PROJECT;
            } else if ($classification == WATER) {
                $type = WATER_PROJECT;
            } else if ($classification == HS) {
                $type = HS_PROJECT;
            }
            return $this->projectRepository->where('property_id', $property_id)->where('status','<>',5)->where('risk_classification_id', $type)->get();
        } else {
            return $this->projectRepository->where('property_id', $property_id)->where('status','<>',5)->where('risk_classification_id', ASBESTOS_PROJECT)->get();
        }
    }

    public function listFireRiskDataCentre() {
        return $this->assessmentRepository->listFireRiskDataCentre();
    }

    public function listHSDataCentre() {
        return $this->assessmentRepository->listHSDataCentre();
    }

    public function listWaterRiskDataCentre() {
        return $this->assessmentRepository->listWaterRiskDataCentre();
    }

    public function listWaterRiskAssessor() {
        return $this->assessmentRepository->listWaterRiskAssessor();
    }
    public function listFireRiskAssessor() {
        return $this->assessmentRepository->listFireRiskAssessor();
    }
    public function listHSAssessor() {
        return $this->assessmentRepository->listHSAssessor();
    }
    public function listOverdueAssessments($type, $limit) {
        $assessment_types = \CompliancePrivilege::getDataCentreAssessmentProjectPermission('survey',$type, 'sql');
        return $this->assessmentRepository->listOverdueAssessments($assessment_types, $type, $limit);
    }

    public function getAsessmentReInspection($datacentreRisk, $clientID = 0, $page = 0, $limit = 2000) {
        $assessment_types = \CompliancePrivilege::getDataCentreAssessmentProjectPermission('survey',$datacentreRisk, 'sql');
        return $this->assessmentRepository->getAsessmentReInspection($assessment_types, $datacentreRisk, $clientID, $page, $limit);
    }

    public function getDecommissionedReasons()
    {
        // TODO: add Reason after
        return DecommissionReason::where('type', 'work_request')->where('parent_id', DECOMMISSION)->get();
    }

    public function createAssessment($classification, array $data)
    {
        try {
            DB::beginTransaction();
            // TODO: search client id
            $prefixRef = '';
            switch ($classification){
                case ASBESTOS:
                    $classification = ASSESSMENT_ASBESTOS_TYPE;
                    break;
                case FIRE:
                    $classification = ASSESSMENT_FIRE_TYPE;
                    $prefixRef = 'FRA';
                    break;
                case GAS:
                    $classification = ASSESSMENT_GAS_TYPE;
                    $prefixRef = 'GRA';
                    break;
                case WATER:
                    $classification = ASSESSMENT_WATER_TYPE;
                    $prefixRef = 'WRA';
                    break;
                case HS:
                    $classification = ASSESSMENT_HS_TYPE;
                    $prefixRef = 'HSA';
                    break;
            }
            $dataAssessment = [
                'client_id' => \CommonHelpers::checkArrayKey($data,'clientKey'),
                'property_id' => \CommonHelpers::checkArrayKey($data,'property_id'),
                'classification' => $classification,
                'type' => \CommonHelpers::checkArrayKey($data,'type'),
                'status' => ASSESSMENT_STATUS_NEW,
                'project_id' => \CommonHelpers::checkArrayKey($data,'project_id'),
                'decommissioned' => 0,
                'lead_by' => \CommonHelpers::checkArrayKey($data,'lead_by'),
                'second_lead_by' => \CommonHelpers::checkArrayKey($data,'second_lead_by'),
                'assessor_id' => \CommonHelpers::checkArrayKey($data,'assessor_id'),
                'quality_checker' => \CommonHelpers::checkArrayKey($data,'quality_checker'),
                'work_request_id' => \CommonHelpers::checkArrayKey($data,'work_request_id'),
                'due_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'due_date')),
                'started_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'started_date')),
                'assess_start_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'assess_start_date')),
                'assess_start_time' => \CommonHelpers::checkArrayKey($data, 'assess_start_time'),
                'assess_finish_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'assess_finish_date')),
                'is_locked' => 0,
                'created_by' => \Auth::user()->id,
            ];

            // Get property information by JSON
            $property = $this->propertyRepository->getProperty($data['property_id'], []);
            $propertyInformation = [
                'property_status' => $property->propertySurvey->property_status ?? '',
                'property_occupied' => $property->propertySurvey->property_occupied ?? '',
                'programme_type' => $property->propertySurvey->programme_type ?? '',
                'asset_class' => $property->asset_class_id,
                'asset_type' => $property->asset_type_id,
                'programme_type_other' => $property->propertySurvey->programme_type_other ?? '',
                'asset_use_primary' => $property->propertySurvey->asset_use_primary ?? '',
                'asset_use_primary_other' => $property->propertySurvey->asset_use_primary_other ?? '',
                'asset_use_secondary' => $property->propertySurvey->asset_use_secondary ?? '',
                'asset_use_secondary_other' => $property->propertySurvey->asset_use_secondary_other ?? '',
                'construction_age' => $property->propertySurvey->construction_age ?? '',
                'construction_type' => $property->propertySurvey->construction_type ?? '',
                'construction_materials' => $property->constructionMaterials->pluck('id'),
                'construction_material_other' => \CommonHelpers::getPropertyOtherMaterial($property),
                'listed_building' => $property->propertySurvey->listed_building ?? '',
                'listed_building_other' => $property->propertySurvey->listed_building_other ?? '',
                'size_floors' => $property->propertySurvey->size_floors ?? '',
                'size_floors_other' => $property->propertySurvey->size_floors_other ?? '',
                'size_staircases' => $property->propertySurvey->size_staircases ?? '',
                'size_staircases_other' => $property->propertySurvey->size_staircases_other ?? '',
                'size_lifts' => $property->propertySurvey->size_lifts ?? '',
                'size_lifts_other' => $property->propertySurvey->size_lifts_other ?? '',
                'electrical_meter' => $property->propertySurvey->electrical_meter ?? '',
                'gas_meter' => $property->propertySurvey->gas_meter ?? '',
                'loft_void' => $property->propertySurvey->loft_void ?? '',
                'size_net_area' => $property->propertySurvey->size_net_area ?? '',
                'size_gross_area' => $property->propertySurvey->size_gross_area ?? '',
                'parking_arrangements' => $property->propertySurvey->parking_arrangements ?? '',
                'parking_arrangements_other' => $property->propertySurvey->parking_arrangements_other ?? '',
                'nearest_hospital' => $property->propertySurvey->nearest_hospital ?? '',
                'restrictions_limitations' => $property->propertySurvey->restrictions_limitations ?? '',
                'unusual_features' => $property->propertySurvey->unusual_features ?? '',
                'size_comments' => $property->propertySurvey->size_comments ?? '',
                'vulnerable_occupant_type' =>  optional($property->vulnerableOccupant)->vulnerable_occupant_type ??
                                                    ($classification == ASSESSMENT_FIRE_TYPE ? self::PRELOAD_VULNERABLE_OCCUPANT_TYPE : ''),
                'avg_vulnerable_occupants' => optional($property->vulnerableOccupant)->avg_vulnerable_occupants ?? '',
                'max_vulnerable_occupants' => optional($property->vulnerableOccupant)->max_vulnerable_occupants ?? '',
                'last_vulnerable_occupants' => optional($property->vulnerableOccupant)->last_vulnerable_occupants ?? '',
                'evacuation_strategy' => optional($property->propertySurvey)->evacuation_strategy ?? '',
                'fra_overall' => optional($property->propertySurvey)->fra_overall ?? '',
                "stairs" => optional($property->propertySurvey)->stairs ?? '',
                "stairs_other" => optional($property->propertySurvey)->stairs_other ?? '',
                "floors" => optional($property->propertySurvey)->floors ?? '',
                "floors_other" => optional($property->propertySurvey)->floors_other ?? '',
                "wall_construction" => optional($property->propertySurvey)->wall_construction ?? '',
                "wall_construction_other" => optional($property->propertySurvey)->wall_construction_other ?? '',
                "wall_finish" => optional($property->propertySurvey)->wall_finish ?? '',
                "wall_finish_other" => optional($property->propertySurvey)->wall_finish_other ?? '',
                "floors_above" => optional($property->propertySurvey)->floors_above ?? '',
                "floors_above_other" => optional($property->propertySurvey)->floors_above_other ?? '',
                "floors_below" => optional($property->propertySurvey)->floors_below ?? '',
                "floors_below_other" => optional($property->propertySurvey)->floors_below_other ?? '',
            ];
            $dataAssessmentInfo = [
                'setting_non_conformities' => (isset($data['setting_non_conformities'])) ? 1 : 0,
                'setting_property_size_volume' => (isset($data['setting_property_size_volume'])) ? 1 : 0,
                'setting_fire_safety' => (isset($data['setting_fire_safety'])) ? 1 : 0,
                'setting_equipment_details' => (isset($data['setting_equipment_details'])) ? 1 : 0,
                'setting_show_vehicle_parking' => (isset($data['setting_show_vehicle_parking'])) ? 1 : 0,
                'setting_hazard_photo_required' => (isset($data['setting_hazard_photo_required'])) ? 1 : 0,
                'setting_assessors_note_required' => (isset($data['setting_assessors_note_required'])) ? 1 : 0,
                'property_information' => json_encode($propertyInformation)
            ];

            $assessment = $this->assessmentRepository->create($dataAssessment);
            $assessment->reference = $prefixRef . $assessment->id;
            $assessment->save();

            $dataAssessmentInfo['assess_id'] = $assessment->id;
            $this->assessmentInfoRepository->create($dataAssessmentInfo);
            $this->downloadRegisterData($assessment, $data);

            // log audit
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_ADD, $assessment->reference, $assessment->property_id);
            DB::commit();

            return \CommonHelpers::successResponse('New Assessment Created Successfully!', $assessment);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create Assessment. Please try again!');
    }

    public function updateAssessment($assess_id, array $data)
    {
        try {
            DB::beginTransaction();
            $dataAssessment = [
                'type' => \CommonHelpers::checkArrayKey($data,'type'),
                'project_id' => \CommonHelpers::checkArrayKey($data,'project_id'),
                'lead_by' => \CommonHelpers::checkArrayKey($data,'lead_by'),
                'client_id' => \CommonHelpers::checkArrayKey($data,'clientKey'),
                'second_lead_by' => \CommonHelpers::checkArrayKey($data,'second_lead_by'),
                'assessor_id' => \CommonHelpers::checkArrayKey($data,'assessor_id'),
                'quality_checker' => \CommonHelpers::checkArrayKey($data,'quality_checker'),
                'work_request_id' => \CommonHelpers::checkArrayKey($data,'work_request_id'),
                'due_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'due_date')),
                'started_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'started_date')),
                'assess_start_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'assess_start_date')),
                'assess_start_time' => \CommonHelpers::checkArrayKey($data, 'assess_start_time'),
                'assess_finish_date' => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'assess_finish_date')),
            ];

            $dataAssessmentInfo = [
                'setting_property_size_volume' => (isset($data['setting_property_size_volume'])) ? 1 : 0,
                'setting_non_conformities' => (isset($data['setting_non_conformities'])) ? 1 : 0,
                'setting_fire_safety' => (isset($data['setting_fire_safety'])) ? 1 : 0,
                'setting_equipment_details' => (isset($data['setting_equipment_details'])) ? 1 : 0,
                'setting_show_vehicle_parking' => (isset($data['setting_show_vehicle_parking'])) ? 1 : 0,
                'setting_hazard_photo_required' => (isset($data['setting_hazard_photo_required'])) ? 1 : 0,
                'setting_assessors_note_required' => (isset($data['setting_assessors_note_required'])) ? 1 : 0,
            ];

            $assessment = $this->assessmentRepository->update($dataAssessment, $assess_id);

            $dataAssessmentInfo['assess_id'] = $assessment->id;
            $this->assessmentInfoRepository->update($dataAssessmentInfo, $assessment->assessmentInfo->id);

            // log audit
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_EDIT, $assessment->reference);
            DB::commit();

            return \CommonHelpers::successResponse('Updated Assessment Successfully!', $assessment);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to update Assessment. Please try again!');
    }
    //download system/equipment/hazard into Assessment
    //todo download Vehicle Parking/Assessment Assembly Points/Fire Exist coming soon
    //todo correct record id i.e update record_id == id + is_locked
    //todo lock data in the register, approval only normal hazards (not temp), decommissioned data will approved also + unlock data
    private function downloadRegisterData($assessment, $data){
        $system_ids = explode(",",$data['list_system']);
        $equipment_ids = explode(",",$data['list_equipment']);
        $hazard_ids = explode(",",$data['list_hazard']);
        $fire_exist_ids = explode(",",$data['list_exist']);
        $assembly_point_ids = explode(",",$data['list_assembly_point']);
        $vehicle_parking_ids = explode(",",$data['list_vehicle']);
        //todo set assess_id
        //specific location + image storage
        $systems_register = $this->systemRepository->listSystemRegisterByID($system_ids, ['complianceDocumentStorage']);
       // dd($systems_register);
        //specific location, parent, equipmentConstruction, cleaning, tempAndPh, equipmentModel, nonconformities + image storage
        $equipments_register = $this->equipmentRepository->listEquipmentRegisterByID($equipment_ids,
            ['parent','equipmentConstruction','cleaning','tempAndPh','equipmentModel','nonconformities','specificLocationValue',
                'equipmentPhotoshineDocumentStorage','equipmentLocationPhotoshineDocumentStorage','equipmentAdditionalPhotoshineDocumentStorage']);
        //todo no relation + image storage
        $hazards_register = $this->hazardRepository->listHazardRegisterByID($hazard_ids,
            ['hazardPhotoshineDocumentStorage','hazardLocationPhotoshineDocumentStorage','hazardAdditionalPhotoshineDocumentStorage', 'hazardSpecificLocationValue']);
        $fire_exists = $this->exitRepository->listFireExistRegisterByID($fire_exist_ids,['fireExistPhotoShineDocumentStorage']);
        $assembly_points = $this->assemblyRepository->listAssemblyPointRegisterByID($assembly_point_ids,['assemblyPointPhotoShineDocumentStorage']);
        $vehicle_parking = $this->vehicleParkingRepository->listVehicleParkingRegisterByID($vehicle_parking_ids,['vehicleParkingPhotoShineDocumentStorage']);
        $this->cloneSystem($systems_register, $assessment);
        $this->cloneEquipment($equipments_register, $assessment);//and nonconformity
        $this->cloneHazard($hazards_register, $assessment);
        $this->cloneFireExist($fire_exists, $assessment);
        $this->cloneAssemblyPoint($assembly_points, $assessment);
        $this->cloneVehicleParking($vehicle_parking, $assessment);
        //area/location no need to clone so in add/edit sections in Assessment will show Area/room in register + area/floor in Assessment
        //todo update showing area/floor in Hazard + 3 tables + equipment add/edit page, check relation also (where assess_id > 0
        //todo check relations in the register i.e add condition assess_id = 0
        //todo when approve assessment, only add new area/floor in Assessment
       // dd($systems_register, $equipments_register, $hazards_register, $assessment->id, $this->hazardRepository->where('assess_id', $assessment->id)->get());
        //update linked equipment, todo check list linked/display linked equipment in add/edit equipment
        $this->correctLinkedEquipmentAndSystemForEquipment($equipments_register, $assessment);
       // dd(1);
        //todo clone data, relation
        //todo correct data after i.e location_id/area_id (no need), images, linked_id for equipment
    }

    //clone and lock data register
    private function cloneSystem($systems, $assessment){
        if(count($systems) > 0){
            $list_ids = [];
            foreach ($systems as $system){
                $list_ids[] = $system->id;
                $data_system = [
                    'decommissioned' => $system->decommissioned ?? 0 ,
                    'assess_id' => $assessment->id ?? 0,
                    'is_locked' => $system->is_locked ?? 0,
                    'reference' => $system->reference ?? NULL,
                    'record_id' => $system->record_id ?? NULL,
                    'property_id' => $system->property_id ?? NULL,
                    'name' => $system->name ?? NULL,
                    'type' => $system->type ?? NULL,
                    'classification' => $system->classification ?? NULL,
                    'comment' => $system->comment ?? NULL,
               // 'photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:2048',
                ];
                $new_system = $this->systemRepository->create($data_system);
                $this->checkMultipleRelations($system->complianceDocumentStorage, 'object_id', $new_system->id);
            }
            //unlock
            $this->systemRepository->whereIn('id', $list_ids)->update(['is_locked' => COMPLIANCE_ASSESSMENT_LOCKED]);
        }
    }

    private function cloneEquipment($equipments, $assessment){
        if(count($equipments) > 0){
            $list_ids = [];
            foreach ($equipments as $equipment){
                $list_ids[] = $equipment->id;
                $data_equipment = [
                    'property_id' => $equipment->property_id ?? NULL,
                    'record_id' => $equipment->record_id ?? NULL,
                    'reference' => $equipment->reference ?? NULL,
                    'is_locked' => $equipment->is_locked ?? 0,
                    'name' => $equipment->name ?? NULL,
                    'assess_id' => $assessment->id ?? NULL,
                    'area_id' => $equipment->area_id ?? NULL,
                    'location_id' => $equipment->location_id ?? NULL,
                    'type' => $equipment->type ?? NULL,
                    'decommissioned' => $equipment->decommissioned ?? NULL,
                    'operational_use' => $equipment->operational_use ?? NULL,
                    'state' => $equipment->state ?? NULL,
                    'reason' => $equipment->reason ?? NULL,
                    'reason_other' => $equipment->reason_other ?? NULL,
                    'parent_id' => $equipment->parent_id ?? NULL,
                    'system_id' => $equipment->system_id ?? NULL,
                    'frequency_use' => $equipment->frequency_use ?? NULL,
                    'extent' => $equipment->extent ?? NULL,
                    'created_by' => $equipment->created_by ?? NULL,
               // 'photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:2048',
                ];
                $new_equipment = $this->equipmentRepository->create($data_equipment);
                $this->checkMultipleRelations($equipment->equipmentPhotoshineDocumentStorage, 'object_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->equipmentLocationPhotoshineDocumentStorage, 'object_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->equipmentAdditionalPhotoshineDocumentStorage, 'object_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->equipmentConstruction, 'equipment_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->cleaning, 'equipment_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->tempAndPh, 'equipment_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->equipmentModel, 'equipment_id', $new_equipment->id);
                $this->checkMultipleRelations($equipment->nonconformities, 'equipment_id', $new_equipment->id, ['assess_id' => $assessment->id ?? NULL]);
                $this->checkMultipleRelations($equipment->specificLocationValue, 'equipment_id', $new_equipment->id);
            }
            $this->equipmentRepository->whereIn('id', $list_ids)->update(['is_locked' => COMPLIANCE_ASSESSMENT_LOCKED]);
        }
    }

    private function cloneHazard($hazards, $assessment){
        if(count($hazards) > 0){
            $list_ids = [];
            foreach ($hazards as $hazard){
                $list_ids[] = $hazard->id;
                $data_hazard = [
                    'record_id' => $hazard->record_id ?? NULL,
                    'is_locked' => $hazard->is_locked ?? 0,
                    'reference' => $hazard->reference ?? NULL,
                    'name' => $hazard->name ?? NULL,
                    'property_id' => $hazard->property_id ?? NULL,
                    'assess_id' => $assessment->id ?? NULL,
                    'assess_type' => $hazard->assess_type ?? NULL,
                    'area_id' => $hazard->area_id ?? NULL,
                    'location_id' => $hazard->location_id ?? NULL,
                    'decommissioned' => $hazard->decommissioned ?? 0,
                    'decommissioned_reason' => $hazard->decommissioned_reason ?? NULL,
                    'is_temp' => $hazard->is_temp ?? 0,
                    'type' => $hazard->type ?? NULL,
                    'reason' => $hazard->reason ?? NULL,
                    'reason_other' => $hazard->reason_other ?? NULL,
                    'likelihood_of_harm' => $hazard->likelihood_of_harm ?? NULL,
                    'hazard_potential' => $hazard->hazard_potential ?? NULL,
                    'photo_override' => $hazard->photo_override ?? NULL,
                    'total_risk' => $hazard->total_risk ?? 0,
                    'extent' => $hazard->extent ?? NULL,
                    'measure_id' => $hazard->measure_id ?? NULL,
                    'act_recommendation_noun' => $hazard->act_recommendation_noun ?? NULL,
                    'act_recommendation_noun_other' => $hazard->act_recommendation_noun_other ?? NULL,
                    'act_recommendation_verb' => $hazard->act_recommendation_verb ?? NULL,
                    'act_recommendation_verb_other' => $hazard->act_recommendation_verb_other ?? NULL,
                    'comment' => $hazard->comment ?? NULL,
                    'is_deleted' => $hazard->is_deleted ?? 0,
                    'created_by' => $hazard->created_by ?? NULL,
                ];
                $new_hazard = $this->hazardRepository->create($data_hazard);
                $this->checkMultipleRelations($hazard->hazardPhotoshineDocumentStorage, 'object_id', $new_hazard->id);
                $this->checkMultipleRelations($hazard->hazardLocationPhotoshineDocumentStorage, 'object_id', $new_hazard->id);
                $this->checkMultipleRelations($hazard->hazardAdditionalPhotoshineDocumentStorage, 'object_id', $new_hazard->id);
                $this->checkMultipleRelations($hazard->hazardSpecificLocationValue, 'hazard_id', $new_hazard->id);
            }
            $this->hazardRepository->whereIn('id', $list_ids)->update(['is_locked' => COMPLIANCE_ASSESSMENT_LOCKED]);
        }
    }

    private function cloneFireExist($fire_exists, $assessment){
        if(count($fire_exists) > 0){
            $list_ids = [];
            foreach ($fire_exists as $fire_exist){
                $list_ids[] = $fire_exist->id;
                $data_fire_exist = [
                    'record_id' => $fire_exist->record_id ?? NULL,
                    'is_locked' => $fire_exist->is_locked  ?? 0,
                    'reference' => $fire_exist->reference  ?? NULL,
                    'name' => $fire_exist->name  ?? NULL,
                    'type' => $fire_exist->type  ?? NULL,
                    'property_id' => $fire_exist->property_id  ?? NULL,
                    'assess_id' => $assessment->id,
                    'area_id' => $fire_exist->area_id ?? NULL,
                    'location_id' => $fire_exist->location_id ?? NULL,
                    'decommissioned' => $fire_exist->decommissioned  ?? 0,
                    'accessibility' => $fire_exist->accessibility ?? NULL,
                    'reason_na' => $fire_exist->reason_na ?? NULL,
                    'reason_na_other' => $fire_exist->reason_na_other ?? NULL,
                    'comment' => $fire_exist->comment ?? NULL,
                    'created_by' => $fire_exist->created_by ?? NULL,
                ];
                $new_fire_exist = $this->exitRepository->create($data_fire_exist);
                $this->checkMultipleRelations($fire_exist->fireExistPhotoShineDocumentStorage, 'object_id', $new_fire_exist->id);
            }
            $this->exitRepository->whereIn('id', $list_ids)->update(['is_locked' => COMPLIANCE_ASSESSMENT_LOCKED]);
        }
    }

    private function cloneAssemblyPoint($assembly_points, $assessment){
        if(count($assembly_points) > 0){
            $list_ids = [];
            foreach ($assembly_points as $assembly_point){
                $list_ids[] = $assembly_point->id;
                $data_assembly_point = [
                    'record_id' => $assembly_point->record_id ?? NULL,
                    'is_locked' => $assembly_point->is_locked  ?? 0,
                    'reference' => $assembly_point->reference  ?? NULL,
                    'name' => $assembly_point->name ?? NULL,
                    'property_id' => $assembly_point->property_id  ?? NULL,
                    'assess_id' => $assessment->id,
                    'area_id' => $assembly_point->area_id ?? NULL,
                    'location_id' => $assembly_point->location_id ?? NULL,
                    'decommissioned' => $assembly_point->decommissioned  ?? 0,
                    'accessibility' => $assembly_point->accessibility ?? NULL,
                    'reason_na' => $assembly_point->reason_na ?? NULL,
                    'reason_na_other' => $assembly_point->reason_na_other ?? NULL,
                    'comment' => $assembly_point->comment ?? NULL,
                    'created_by' => $assembly_point->created_by ?? NULL,
                ];
                $new_assembly_point = $this->assemblyRepository->create($data_assembly_point);
                $this->checkMultipleRelations($assembly_point->assemblyPointPhotoShineDocumentStorage, 'object_id', $new_assembly_point->id);
            }
            $this->assemblyRepository->whereIn('id', $list_ids)->update(['is_locked' => COMPLIANCE_ASSESSMENT_LOCKED]);
        }
    }

    private function cloneVehicleParking($vehicle_parking, $assessment){
        if(count($vehicle_parking) > 0){
            $list_ids = [];
            foreach ($vehicle_parking as $vp){
                $list_ids[] = $vp->id;
                $data_vehicle_parking = [
                    'record_id' => $vp->record_id ?? NULL,
                    'is_locked' => $vp->is_locked  ?? 0,
                    'reference' => $vp->reference ?? NULL,
                    'name' => $vp->name ?? NULL,
                    'property_id' => $vp->property_id  ?? NULL,
                    'assess_id' => $assessment->id,
                    'area_id' => $vp->area_id ?? NULL,
                    'location_id' => $vp->location_id ?? NULL,
                    'decommissioned' => $vp->decommissioned  ?? 0,
                    'accessibility' => $vp->accessibility ?? NULL,
                    'reason_na' => $vp->reason_na ?? NULL,
                    'reason_na_other' => $vp->reason_na_other ?? NULL,
                    'comment' => $vp->comment ?? NULL,
                    'created_by' => $vp->created_by ?? NULL,
                ];
                $new_vehicle_parking = $this->vehicleParkingRepository->create($data_vehicle_parking);
                $this->checkMultipleRelations($vp->vehicleParkingPhotoShineDocumentStorage, 'object_id', $new_vehicle_parking->id);
            }
            $this->vehicleParkingRepository->whereIn('id', $list_ids)->update(['is_locked' => COMPLIANCE_ASSESSMENT_LOCKED]);
        }
    }

    public function correctLinkedEquipmentAndSystemForEquipment($equipments, $assessment){
        $current_links = $current_links_hot_parent = $current_links_cold_parent = $current_sys_links = $list_check= $list_check_hot_parent = $list_check_cold_parent = $list_sys_check = [];
        foreach ($equipments as $eq){
            if(isset($eq->parent_id) && $eq->parent_id > 0 && isset($eq->parent)){
                $current_links[$eq->id] = $eq->parent->record_id;
                $list_check[$eq->id] = $eq->parent->id;
            }
            if(isset($eq->hot_parent_id) && $eq->hot_parent_id > 0 && isset($eq->hotParent)){
                $current_links_hot_parent[$eq->id] = $eq->hotParent->record_id;
                $list_check_hot_parent[$eq->id] = $eq->hotParent->id;

            }

            if(isset($eq->cold_parent_id) && $eq->cold_parent_id > 0 && isset($eq->coldParent)){
                $current_links_cold_parent[$eq->id] = $eq->coldParent->record_id;
                $list_check_cold_parent[$eq->id] = $eq->coldParent->id;
            }
            if(isset($eq->system_id) && $eq->system_id > 0 && isset($eq->system)){
                $current_sys_links[$eq->id] = $eq->system->record_id;
                $list_sys_check[$eq->id] = $eq->system->id;
            }
        }
        //correct linked equipment
        if(count($current_links) > 0){
            $new_parents = $this->equipmentRepository->whereIn('record_id', $current_links)->where('assess_id', $assessment->id)->get()->pluck('id', 'record_id')->toArray();
            if(count($new_parents)){
                foreach ($current_links as $eq_id => $parent_record_id){
                    if(array_key_exists($parent_record_id, $new_parents)){
                        $this->equipmentRepository->where(['assess_id' => $assessment->id, 'parent_id' => $list_check[$eq_id]])->update(['parent_id' => $new_parents[$parent_record_id]]);
                    }
                }
            }
        }
        if(count($current_links_hot_parent) > 0){
            $new_parents = $this->equipmentRepository->whereIn('record_id', $current_links_hot_parent)->where('assess_id', $assessment->id)->get()->pluck('id', 'record_id')->toArray();
            if(count($new_parents)){
                foreach ($current_links_hot_parent as $eq_id => $parent_record_id){
                    if(array_key_exists($parent_record_id, $new_parents)){
                        $this->equipmentRepository->where(['assess_id' => $assessment->id, 'hot_parent_id' => $list_check_hot_parent[$eq_id]])->update(['hot_parent_id' => $new_parents[$parent_record_id]]);
                    }
                }
            }
        }


        if(count($current_links_cold_parent) > 0){
            $new_parents = $this->equipmentRepository->whereIn('record_id', $current_links_cold_parent)->where('assess_id', $assessment->id)->get()->pluck('id', 'record_id')->toArray();
            if(count($new_parents)){
                foreach ($current_links_cold_parent as $eq_id => $parent_record_id){
                    if(array_key_exists($parent_record_id, $new_parents)){
                        $this->equipmentRepository->where(['assess_id' => $assessment->id, 'cold_parent_id' => $list_check_cold_parent[$eq_id]])->update(['cold_parent_id' => $new_parents[$parent_record_id]]);
                    }
                }
            }
        }
        //correct linked system
        if(count($current_sys_links) > 0){
            $new_parents = $this->systemRepository->whereIn('record_id', $current_sys_links)->where('assess_id', $assessment->id)->get()->pluck('id', 'record_id')->toArray();
            if(count($new_parents)){
                foreach ($current_sys_links as $eq_id => $parent_record_id){
                    if(array_key_exists($parent_record_id, $new_parents)){
                        $this->equipmentRepository->where(['assess_id' => $assessment->id, 'system_id' => $list_sys_check[$eq_id]])->update(['system_id' => $new_parents[$parent_record_id]]);
                    }
                }
            }
        }
    }

    //set new attribute for replicate relation ex: $compliance_storage->object_id = $new_system_id
    public function checkMultipleRelations($relations, $key = '', $value = NULL, $other = []) {
        if (!is_null($relations)) {
            if($relations instanceof Collection) {
                foreach ($relations as $relation) {
                    $this->replicateRelation($relation, $key, $value, $other);
                }
            } else {
                $this->replicateRelation($relations, $key, $value, $other);
            }
        }
    }

    public function replicateRelation($relation, $key, $value, $other) {
        $newRelation = $relation->replicate();
        if ($key && isset($newRelation->{$key})) {
            $newRelation->{$key} = $value;
        }
        if(count($other)){
            foreach ($other as $key => $value){
                if ($key && isset($newRelation->{$key})) {
                    $newRelation->{$key} = $value;
                }
            }
        }
        $newRelation = $newRelation->save();
    }

    public function getAssessmentDetail($assess_id, $relations = []){
        return $this->assessmentRepository->getAssessmentDetail($assess_id, $relations);
    }

    public function getEquipmentDetail($equip_id, $relations = []){
        return $this->equipmentRepository->getEquipmentDetail($equip_id, $relations);
    }

    public function getQuestionnaire($assess_id, $assess_type){
        return $this->assessmentSectionRepository->getQuestionnaire($assess_id, $assess_type);
    }

    public function getManagementInfoQueries($assess_id)
    {
        return $this->managementQuestionRepository->getManagementQuestionsByAssessId($assess_id);
    }

    public function getOtherInfoQueries($assess_id)
    {
        return $this->otherQuestionRepository->getOtherQuestionsByAssessId($assess_id);
    }

    public function getAsbestosItem($property_id) {
        // $assessment_register_area_ids = $this->areaRepository->getAssessmentRegisterAreaId($assess_id);
        $items = $this->itemRepository->getItemInProperty($property_id);
        return $items;
    }

    public function getAreaAssessment($assess_id, $property_id, $relations = []){
        return $this->areaRepository->getRegisterAssessmentArea($property_id, $assess_id);
    }

    public function getLocationsByAssessmentAndArea($assess_id, $area_id)
    {
        return $this->locationRepository->getLocationsByAssessmentIdAndAreaId($assess_id, $area_id);
    }

    public function getLocationAssessment($assess_id, $property_id = 0, $relations = []) {
        return $this->locationRepository->getAssessmentLocation($assess_id, $property_id, $relations);
    }

    public function getAllAssessmentSections()
    {
        return AssessmentSection::all();
    }

    public function getAllAssessmentQuestions()
    {
        return AssessmentQuestion::all();
    }

    public function getDataAssessmentQuestions($id)
    {
        return AssessmentQuestion::find($id);
    }

    public function getAllAssessmentStatementAnswers()
    {
        return AssessmentStatementAnswer::all();
    }

    public function getAllAssessmentAnswers()
    {
        return AssessmentAnswer::all();
    }

    public function updateQuestionnaire($data, $assess_id, $assess_type){
        try{
            \DB::beginTransaction();
            if (!is_null($data) and count($data) > 0) {
                $sections = $this->getQuestionnaire($assess_id, $assess_type);
                $questions = $data['question'];
                $answers = $data['answer'];
                $comments = $data['comment'];
                $observations = $data['observations'] ?? null;

                // delete before insert value
                AssessmentValue::where('assess_id', $assess_id)->forcedelete();
                AssessmentResult::where('assess_id', $assess_id)->forcedelete();
                // update question and answer
                foreach ($questions as $question) {
                    if ($question != OTHER_HAZARD_IDENTIFIED_QUESTION_ID) {
                        $dataAssessmentAnswer = [
                            'assess_id' => $assess_id,
                            'question_id' => $question,
                            'answer_id' => $answers[$question] ?? 0,
                            'other' => $comments[$question] ?? null,
                        ];
                        if ($assess_type == ASSESSMENT_FIRE_TYPE) {
                            $dataAssessmentAnswer['observations'] = isset($observations[$question]) ?$observations[$question] : null;
                        }
                        AssessmentValue::updateOrCreate(['assess_id' => $assess_id, 'question_id' => $question], $dataAssessmentAnswer);
                    } else {
                        $other_hazard_answers = $answers[$question];
                        $other_hazard_comments = $comments[$question];
                        if (count($other_hazard_answers)) {
                            foreach ($other_hazard_answers as $key => $other_hazard_answer) {
                                $dataAssessmentAnswer = [
                                    'assess_id' => $assess_id,
                                    'question_id' => $question,
                                    'answer_id' => $other_hazard_answer ?? 0,
                                    'other' => $other_hazard_comments[$key] ?? null,
                                ];
                                if ($assess_type == ASSESSMENT_FIRE_TYPE) {
                                    $dataAssessmentAnswer['observations'] = isset($observations[$question]) ?$observations[$question] : null;
                                }
                                AssessmentValue::create($dataAssessmentAnswer);
                            }
                        }
                    }
                }
                $data_result = $this->saveScore($sections, $data, $assess_id);
                if(count($data_result) > 0){
                    foreach ($data_result as $result){
                        AssessmentResult::updateOrCreate(['assess_id' => $assess_id, 'section_id' => $result['section_id']],$result);
                    }

                    // log audit
                    $assessment = $this->assessmentRepository->getAssessmentDetail($assess_id, []);
                    $comment = \Auth::user()->full_name . " updated questionnaire";
                    \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);
                }
            }
            \DB::commit();
            return $response = \CommonHelpers::successResponse('Updated Assessment Successfully!');
        } catch (\Exception $e){
            dd($e);
            \DB::rollback();
            Log::error($e);
            return \CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Failed to updating Audit Assessment! Please try again');
        }
    }

    //save score for parent/children section
    public function saveScore($sections, $data, $assess_id){

        foreach ($sections as $parent_sections){
            $data_result[$parent_sections->id]['assess_id'] = $assess_id;
            $data_result[$parent_sections->id]['section_id'] = $parent_sections->id;
            $parent_total_question = 0;
            $parent_total_yes = 0;
            $parent_total_no = 0;
            $parent_total_score = 0;
            foreach ($parent_sections->children as $sections){
                $data_result[$sections->id]['assess_id'] = $assess_id;
                $data_result[$sections->id]['section_id'] = $sections->id;
                $total_question = $data['total_question'][$sections->id] ?? 0;

                $total_yes = $data['score'][$sections->id] ?? 0;
                $total_no = $total_question - $total_yes > 0 ? $total_question - $total_yes : 0;
                $data_result[$sections->id]['total_question'] = $total_question;
                $data_result[$sections->id]['total_yes'] = $total_yes;
                $data_result[$sections->id]['total_no'] = $total_no;
                $data_result[$sections->id]['total_score'] = $total_yes;

                if (($total_question == 0) and ($total_yes == 0)) {
                    $total_question = count($sections->questions) ?? 0;
                }
                $parent_total_question += $total_question;
                $parent_total_yes += $total_yes;
                $parent_total_no += $total_no;
                $parent_total_score += $total_yes;
            }
            $data_result[$parent_sections->id]['total_question'] = $parent_total_question;
            $data_result[$parent_sections->id]['total_yes'] = $parent_total_yes;
            $data_result[$parent_sections->id]['total_no'] = $parent_total_no;
            $data_result[$parent_sections->id]['total_score'] = $parent_total_score;
        }
        return $data_result;
    }

    public function getScoreQuestionnaire($sections){
        foreach ($sections as $section){
            $total_question = $total_score = 0;
            if(isset($section->children) && count($section->children)){
                foreach ($section->children as $parent){
                    foreach ($parent->questions as $question){
                        $total_question ++;
                        $total_score = $question->aaa;
                    }
                }
            }
        }
    }

    public function getAssessments($property_id, $type, $decommissioned = 0, $client_id = null)
    {
        return $this->assessmentRepository
                    ->getAssessmentsByPropertyIdAndClassificationAndDecommissionedAndClientId($property_id, $type, $decommissioned, $client_id);
    }

    public function getEquipmentDropdownData($dropdown_id) {
        return $this->equipmentRepository->getEquipmentDropdownData($dropdown_id);
    }

    public function updateObjectiveScope($assess_id, $objectiveScope, $executiveSummary, $management_answer = [], $management_answer_other = [], $other_answer = [])
    {
        try {
            DB::beginTransaction();
            $assessment = $this->assessmentRepository->getAssessmentDetail($assess_id, []);
            $this->assessmentInfoRepository->update(
                [
                    'executive_summary' => $executiveSummary,
                    'objective_scope' => $objectiveScope
                ],
                $assessment->assessmentInfo->id);

            // FRA
            if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
                // Management Info
                foreach ($management_answer as $key => $value) {
                    $question = $this->managementQuestionRepository->find($key);
                    $this->managementValueRepository->updateOrCreate(['question_id' => $key, 'assess_id'  =>$assess_id], [
                        'assess_id'  =>$assess_id,
                        'question_id' => $key,
                        'answer_id' => $question->answer_type == 1 ? $value : ($question->answer_type == 3 ? implode(',', $value) : null),
                        'answer_other' => $question->answer_type == 2 ? $value : $management_answer_other[$key],
                    ]);
                }

                // Management Info
                foreach ($other_answer as $key => $value) {
                    $question = $this->otherQuestionRepository->find($key);
                    $this->otherValueRepository->updateOrCreate(['question_id' => $key, 'assess_id'  =>$assess_id], [
                        'assess_id'  =>$assess_id,
                        'question_id' => $key,
                        'answer_id' => $question->answer_type == 1 ? $value : null,
                        'answer_other' => $question->answer_type == 2 ? $value : $management_answer_other[$key],
                    ]);
                }
            }

            // log audit
            $comment = \Auth::user()->full_name . " updated objective_scope";
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_EDIT, $assessment->reference, $assessment->property_id, $comment);
            DB::commit();

            return \CommonHelpers::successResponse('Updated Objective/scope Successfully!', $assessment);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to update Objective/scope. Please try again!');
    }


    public function createArea($create_area_data){
        $area = $this->areaRepository->create($create_area_data);
        $area->record_id = $area->id;
        $area->reference = "AF" . $area->id;
        $area->save();
        return $area->id;
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

    public function loadDropdownText($dropdown_item_id, $parent_id = 0) {
        $data = null;
        switch ($dropdown_item_id) {
            case SPECIFIC_LOCATION_ID:
                $data = HazardSpecificLocation::with('allChildrens')->where('parent_id', $parent_id)->where('decommissioned',0)->get();
                break;
        }
        return is_null($data) ? null : $data;
    }

    public function updatePropertyInformation($assess_id, $request)
    {
        try {
            $dataInput = $request->except(['_token', 'property_assess_img', 'vulnerable_occupant_types-other']);

            $assessment = $this->assessmentRepository->getAssessmentDetail($assess_id, []);

            // Hotfix
            if (!isset($dataInput['vulnerable_occupant_type'])) {
                $dataInput['vulnerable_occupant_type'] = null;
            }

            if (!isset($dataInput['construction_materials'])) {
                $dataInput['construction_materials'] = null;
            }
            $dataInput['construction_material_other'] = $dataInput['construction_materials-other'] ?? null;
            $dataInput['stairs']            = \CommonHelpers::getMultiselectDataContruction(isset($dataInput['stairs']) ? $dataInput['stairs'] : null);
            $dataInput['stairs_other']       = \CommonHelpers::checkArrayKey($dataInput,'stairs-other');
            $dataInput['floors' ]           = \CommonHelpers::getMultiselectDataContruction(isset($dataInput['floors']) ? $dataInput['floors'] : null);
            $dataInput['floors_other' ]      = \CommonHelpers::checkArrayKey($dataInput,'floors-other');
            $dataInput['wall_construction']            = \CommonHelpers::getMultiselectDataContruction(isset($dataInput['wall_construction']) ? $dataInput['wall_construction'] : null);
            $dataInput[ 'wall_construction_other']       = \CommonHelpers::checkArrayKey($dataInput,'wall_construction-other');
            $dataInput ['wall_finish']            = \CommonHelpers::getMultiselectDataContruction(isset($dataInput['wall_finish']) ? $dataInput['wall_finish'] : null);
            $dataInput ['wall_finish_other']       = \CommonHelpers::checkArrayKey($dataInput,'wall_finish-other');
           // if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
           //     $dataInput['property_status'] = null;
           //     $dataInput['property_occupied'] = null;
           //     $dataInput['construction_materials'] = null;
           //     $dataInput['size_floors'] = null;
           //     $dataInput['electrical_meter'] = null;
           //     $dataInput['gas_meter'] = null;
           // }
            if ($assessment->classification == ASSESSMENT_WATER_TYPE) {
                $dataInput['evacuation_strategy'] = null;
                $dataInput['fra_overall'] = null;
                $dataInput['stairs'] = null;
                $dataInput['stairs_other'] = null;
                $dataInput['floors'] = null;
                $dataInput['floors_other'] = null;
                $dataInput['wall_construction'] = null;
                $dataInput['wall_construction_other'] = null;
                $dataInput['wall_finish'] = null;
                $dataInput['wall_finish_other'] = null;
                $dataInput['floors_above'] = null;
                $dataInput['floors_above_other'] = null;
                $dataInput['floors_below'] = null;
                $dataInput['floors_below_other'] = null;
               // $dataInput['property_type'] = null;
                $dataInput['asset_class'] = null;
                $dataInput['asset_type'] = null;
            }

            // Update property information
            $this->assessmentInfoRepository->update(['property_information' => json_encode($dataInput)], $assessment->assessmentInfo->id);
            // Update property photography
            $image = $request->property_assess_img;
            if (!is_null($image)) {
                $savePropertyImage = \CommonHelpers::saveFileShineDocumentStorage($image, $assess_id, PROPERTY_ASSESS_IMAGE);
            }

            // log audit
            $comment = \Auth::user()->full_name . " updated property information";
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_EDIT, $assessment->reference, $assessment->property_id, $comment);

            return \CommonHelpers::successResponse('Updated Property Information Successfully!', $assessment);
        } catch (\Exception $exception) {
            Log::error($exception);
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to update Property Information. Please try again!');
    }


    public function getAssessmentNotes($propety_id, $assess_id){
        return $this->assessmentNoteDocumentRepository->getAssessmentNotes($propety_id,$assess_id);
    }

    public function getAssessmentPlans($propety_id, $assess_id)
    {
        return $this->assessmentPlanDocumentRepository->getAssessmentPlans($propety_id, $assess_id);
    }

    public function recommissionEquipment($equipment){
        try {
            if($equipment->decommissioned == EQUIPMENT_DECOMMISSION){
                $equipment->decommissioned = EQUIPMENT_UNDECOMMISSION;
                $equipment->save();

                // log audit
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_RECOMMISSION, $equipment->reference);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Equipment. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Equipment Successfully!', $equipment);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Equipment. Please try again!');
    }

    public function decommissionAssessment($assessment, $reason_decommissioned)
    {
        try {
            if($assessment->decommissioned == HAZARD_UNDECOMMISSION){
                $assessment->decommissioned = HAZARD_DECOMMISSION;
                if ($reason_decommissioned) {
                    $assessment->reason_decommissioned = $reason_decommissioned;
                }
                $assessment->save();

                // log audit
                \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_DECOMMISSION, $assessment->reference);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Assessment. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Assessment Successfully!', $assessment);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Assessment. Please try again!');
    }

    public function recommissionAssessment($assessment)
    {
        try {
            if($assessment->decommissioned == HAZARD_DECOMMISSION){
                $assessment->decommissioned = HAZARD_UNDECOMMISSION;
                $assessment->reason_decommissioned = null;
                $assessment->save();
                \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_RECOMMISSION, $assessment->reference);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Assessment. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Recommissioned Assessment Successfully!', $assessment);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to recommission Assessment. Please try again!');
    }

    public function cancelAssessment($assessment){
        try {
            DB::beginTransaction();
            $assessment->status = ASSESSMENT_STATUS_READY_FOR_QA;
            $assessment->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
            $assessment->save();
            $this->unLockAssessment($assessment->id);
            //log audit

            $comment = \Auth::user()->full_name  . " canceled assessment "  . $assessment->reference;
            \CommonHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_CANCELED, $assessment->reference, $assessment->property_id ,$comment, 0 ,$assessment->property_id);
            DB::commit();
            return $response = \CommonHelpers::successResponse('Canceled assessment successful!');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to cancel assessment. Please try again!');
        }

    }

    public function decommissionEquipment($equipment){
        try {
            if($equipment->decommissioned == EQUIPMENT_UNDECOMMISSION){
                $equipment->decommissioned = EQUIPMENT_DECOMMISSION;
                $equipment->save();

                // log audit
                \ComplianceHelpers::logAudit(EQUIPMENT_TYPE, $equipment->id, AUDIT_ACTION_DECOMMISSION, $equipment->reference);
            } else {
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Equipment. Please try again!');
            }
            return $response = \CommonHelpers::successResponse('Decommissioned Equipment Successfully!', $equipment);
        } catch (\Exception $e){
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to decommission Equipment. Please try again!');
    }

    public function sendAssessment($assessment)
    {
        try {
            DB::beginTransaction();
            $assessment->is_locked = SURVEY_LOCKED;
            $assessment->status = ASSESSMENT_STATUS_LOCKED;
            $assessment->sent_out_date = Carbon::now()->timestamp;
            $assessment->save();

            // log audit
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_SEND, $assessment->reference);
            // Lock all items of Assessment
            $this->lockAssessment($assessment->id);

            DB::commit();

            return $response = \CommonHelpers::successResponse('Sent Assessment Successfully!', $assessment);
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to send Assessment. Please try again!');
    }


    public function updateOrCreatePropertyPlan($data, $id = null) {

        if (!empty($data)) {
            // SitePlanDocument
            $dataPlan = [
                "property_id" => $data['property_id'] ?? 0,
                "plan_reference" => $data['plan_reference'] ?? 0,
                "description" => $data['description'] ?? '',
                "assess_id" => $data['assess_id'] ?? '',
                "plan_date" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'plan_date')),
            ];
            $type_log = ($data['assess_id'] == 0) ? SITE_PLAN_DOCUMENT_TYPE : SURVEY_PLAN_DOCUMENT_TYPE;

            try {
                if (is_null($id)) {
                    $dataPlan["added_by"] = \Auth::user()->id;
                    if($data['type'] == "assessor_note"){
                        $assess_plan = $this->assessmentNoteDocumentRepository->createPropertyNote($dataPlan);
                    }else{
                        $assess_plan = $this->assessmentPlanDocumentRepository->createPropertyPlan($dataPlan);
                    }
                    if ($assess_plan) {
                        if($data['type'] == "assessor_note"){
                            $planRef = "PP" . $assess_plan->id;
                        } else{
                            $planRef = "SP" . $assess_plan->id;
                        }
                        if($data['type'] == "assessor_note"){
                            $this->assessmentNoteDocumentRepository->updatePropertyNote($assess_plan->id,['reference' => $planRef]);
                        }else{
                            $this->assessmentPlanDocumentRepository->updatePropertyPlan($assess_plan->id,['reference' => $planRef]);
                        }
                        //log audit
                        $comment = \Auth::user()->full_name . " add new plan " . $assess_plan->name;
                        \ComplianceHelpers::logAudit($type_log, $assess_plan->id, AUDIT_ACTION_ADD, $planRef, $dataPlan['assess_id'], $comment, 0 , $dataPlan['property_id']);
                    }
                    $response = \CommonHelpers::successResponse('Upload plan document successfully !');
                } else {
                    if($data['type'] == "assessor_note"){
                        $this->assessmentNoteDocumentRepository->updatePropertyNote($id,$dataPlan);
                    }else{
                        $this->assessmentPlanDocumentRepository->updatePropertyPlan($id,$dataPlan);
                    }
                    if($data['type'] == "assessor_note"){
                        $assess_plan = $this->assessmentNoteDocumentRepository->getFirstAssessmentNoteDocument($id);
                    }else{
                        $assess_plan = $this->assessmentPlanDocumentRepository->getFirstAssessmentPlanDocument($id);
                    }
                    $response = \CommonHelpers::successResponse('Update plan document successfully !');

                    //log audit
                    $comment = \Auth::user()->full_name . " edited plan " . $assess_plan->name;
                    \ComplianceHelpers::logAudit($type_log, $assess_plan->id, AUDIT_ACTION_EDIT, $assess_plan->reference, $dataPlan['assess_id'], $comment, 0 , $dataPlan['property_id']);
                }

                if (isset($data['document'])) {
                    if($data['type'] == "assessor_note"){
                        $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $assess_plan->id, NOTE_FILE_ASSESSMENT,\CommonHelpers::checkArrayKey3($data,'assess_id'));
                        $dataUpdateImg = [
                            'document_present' => 1,
                        ];
                        $this->assessmentNoteDocumentRepository->updatePropertyNote($assess_plan->id,$dataUpdateImg);
                    }else{
                        $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $assess_plan->id, PLAN_FILE_ASSESSMENT,\CommonHelpers::checkArrayKey3($data,'assess_id'));
                        $dataUpdateImg = [
                            'document_present' => 1,
                        ];
                        $this->assessmentPlanDocumentRepository->updatePropertyPlan($assess_plan->id,$dataUpdateImg);
                    }
                }
                return $response;
            } catch (\Exception $e) {
                Log::error($e);
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload plan document. Please try again !');
            }
        }
    }

    public function getFireExistRegister($property_id, $relations = []){
        return $this->exitRepository->getFireExistRegister($property_id, $relations);
    }

    public function getAssemblyPointRegister($property_id, $relations = []){
        return $this->assemblyRepository->getAssemblyPointRegister($property_id, $relations);
    }

    public function getVehicleParkingRegister($property_id, $relations = []){
        return $this->vehicleParkingRepository->getVehicleParkingRegister($property_id, $relations);
    }

    public function getSentOutAssessments()
    {
        $assessor = Auth::user();

        $assessments = $this->assessmentRepository->getAssessmentsByAssessorId($assessor->id);

        if (count($assessments)) {
            return $assessments->pluck('id');
        } else {
            return [];
        }
    }

    public function getApprovalAssessment($user_id = null)
    {
        if (\CommonHelpers::isSystemClient()) {
            // property privilege
            $permission_join = \CompliancePrivilege::getPropertyPermission();

             return $this->assessmentRepository->getWaitingApprovalAssessments($user_id);
        } else {
            $query = $this->assessmentRepository->where('status', ASSESSMENT_STATUS_PUBLISHED)
                ->where('decommissioned', SURVEY_UNDECOMMISSION)
                ->where('client_id', \Auth::user()->client_id);
            if (!empty($user_id)) {
                $query = $query->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    // $query->orWhere(['quality_id' => $user_id]);
                });
            }
            return $query->get();
        }

    }

    public function rejectAssessment($assessment, array $data)
    {
        try {
            DB::beginTransaction();
            $due_date_raw = $data['due_date'] ?? Carbon::now()->format('d/m/Y');
            if (isset($data['due_date'])) {
                $due_date = Carbon::createFromFormat('d/m/Y', $due_date_raw );
            } else {
                $due_date = Carbon::now();
            }

            // unlock assessment
            if (!$this->unlockAssessment($assessment->id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to reject assessment. Please try again !');
            }
            $assessment->due_date = $due_date->timestamp;
            $assessment->status = ASSESSMENT_STATUS_REJECTED;
            $assessment->is_locked = SURVEY_UNLOCKED;
            $assessment->assessmentInfo->comment = \CommonHelpers::checkArrayKey($data,'comment');
            $assessment->assessmentInfo->save();
            $assessment->save();

            // log audit
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_REJECTED, $assessment->reference, $assessment->property_id);
            DB::commit();

            return \CommonHelpers::successResponse('Reject assessment successfully !');
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
        }

        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to reject assessment. Please try again !');
    }

    public function approvalAssessment($assessment)
    {
        try {
            DB::beginTransaction();

            // Create new Area
            $areas = $assessment->areas;
            foreach ($areas as $area) {
                $create_area_data = [
                    'reference' => $area->reference,
                    'record_id' => $area->record_id,
                    'property_id' => $area->property_id,
                    'survey_id' => 0,
                    'assess_id' => 0,
                    'area_reference' => $area->area_reference,
                    'description' => $area->description,
                    'state' => $area->state,
                    'decommissioned' => 0,
                    'created_by' => $area->created_by,
                ];
                $this->areaRepository->create($create_area_data);
            }
            // Create new Location
            $locations = $assessment->locations;
            foreach ($locations as $location) {
                $create_data_location = [
                    'reference' => $location->reference,
                    'record_id' => $location->record_id,
                    'area_id' => $this->areaRepository->getRegisterAreaByRecordId($location->area->record_id ?? 0),
                    'survey_id' => 0,
                    'assess_id' => 0,
                    'property_id' => $location->property_id,
                    'state' => $location->state,
                    'description' => $location->description,
                    'location_reference' => $location->location_reference,
                    'created_by' => $location->created_by
                ];

                $location = $this->locationRepository->create($create_data_location);
                $this->locationRepository->updateOrCreateLocationInfo($location->id, []);
                $this->locationRepository->updateOrCreateLocationVoid($location->id, []);
                $this->locationRepository->updateOrCreateLocationConstruction($location->id, []);
            }

            // Fire Exits, Assembly Point, Vehicle Parking
            $fireExits = $this->exitRepository->getByAssessId($assessment->id);
            if (!$this->pullFireExitsToRegister($fireExits, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            $assemblyPoints = $this->assemblyRepository->getByAssessId($assessment->id);
            if (!$this->pullAssemblyPointsToRegister($assemblyPoints, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            $vehicleParking = $this->vehicleParkingRepository->getByAssessId($assessment->id);
            if (!$this->pullVehicleParkingToRegister($vehicleParking, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            // System
            $systems = $this->systemRepository->getByAssessId($assessment->id);
            if (!$this->pullSystemsToRegister($systems, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            // Equipment
            $equipments = $this->equipmentRepository->getByAssessId($assessment->id);
            if (!$this->pullEquipmentsToRegister($equipments, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            // correct all linked parent equipment
            // foreach ($equipments as $equipment) {
            //     if (!$this->correctPulledEquipmentParentId($equipment)) {
            //         DB::rollBack();
            //         return \CommonHelpers::failResponse(STATUS_FAIL,'Have something wrong when approval assessment. Please try again !');
            //     }
            // }

            // Hazard
            $hazards = $this->hazardRepository->getByAssessId($assessment->id);
            if (!$this->pullHazardsToRegister($hazards, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            // Nonconformites
            $nonconformities = $this->nonconformityRepository->getByAssessId($assessment->id);
            if (!$this->pullNonconformitesToRegister($nonconformities, $assessment->property_id)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            // Update property info
            if (!$this->pullPropertyInformation($assessment)) {
                DB::rollBack();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
            }

            $assessment->status = ASSESSMENT_STATUS_COMPLETED;
            $assessment->completed_date = Carbon::now()->timestamp;
            $assessment->save();

            DB::commit();

            // log audit
            \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_APPROVED, $assessment->reference, $assessment->property_id);
            return \CommonHelpers::successResponse('Approval assessment successfully !');
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
        }
        return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to approval assessment. Please try again !');
    }

    private function pullPropertyInformation($assessment)
    {
        $property = $assessment->property;
        if ($property) {
            $propertyInfo = json_decode(($assessment->assessmentInfo->property_information ?? ''), true);
            if ($propertyInfo) {
                try {
                    $result = PropertySurvey::updateOrCreate(['property_id' => $assessment->property_id], [
                        'property_status' => $propertyInfo['property_status'] ?? NULL,
                        'property_occupied' => $propertyInfo['property_occupied'] ?? NULL,
                        'programme_type' => $propertyInfo['programme_type'] ?? NULL,
                        'programme_type_other' => $propertyInfo['programme_type_other'] ?? NULL,
                        'asset_use_primary' => $propertyInfo['asset_use_primary'] ?? NULL,
                        'asset_use_primary_other' => $propertyInfo['asset_use_primary_other'] ?? NULL,
                        'asset_use_secondary' => $propertyInfo['asset_use_secondary'] ?? NULL,
                        'asset_use_secondary_other' => $propertyInfo['asset_use_secondary_other'] ?? NULL,
                        'construction_age' => $propertyInfo['construction_age'] ?? NULL,
                       // 'construction_type' => $propertyInfo['construction_type'],
                        'listed_building' => $propertyInfo['listed_building'] ?? NULL,
                        'listed_building_other' => $propertyInfo['listed_building_other'] ?? NULL,
                        'size_floors' => $propertyInfo['size_floors'] ?? NULL,
                        'size_floors_other' => $propertyInfo['size_floors_other'] ?? NULL,
                        'size_staircases' => $propertyInfo['size_staircases'] ?? NULL,
                        'size_staircases_other' => $propertyInfo['size_staircases_other'] ?? NULL,
                        'size_lifts' => $propertyInfo['size_lifts'] ?? NULL,
                        'size_lifts_other' => $propertyInfo['size_lifts_other'] ?? NULL,
                        'electrical_meter' => $propertyInfo['electrical_meter'] ?? NULL,
                        'gas_meter' => $propertyInfo['gas_meter'] ?? NULL,
                        'loft_void' => $propertyInfo['loft_void'] ?? NULL,
                        'size_net_area' => $propertyInfo['size_net_area'] ?? NULL,
                        'size_gross_area' => $propertyInfo['size_gross_area'] ?? NULL,
                        'parking_arrangements' => $propertyInfo['parking_arrangements'] ?? NULL,
                        'parking_arrangements_other' => $propertyInfo['parking_arrangements_other'] ?? NULL,
                        'nearest_hospital' => $propertyInfo['nearest_hospital'] ?? NULL,
                        'restrictions_limitations' => $propertyInfo['restrictions_limitations'] ?? NULL,
                        'unusual_features' => $propertyInfo['unusual_features'] ?? NULL,
                        'evacuation_strategy' => $propertyInfo['evacuation_strategy'] ?? NULL,
                        'fra_overall' => $propertyInfo['fra_overall'] ?? NULL,
                        "stairs" => $propertyInfo['stairs'] ?? NULL,
                        "stairs_other" => $propertyInfo['stairs_other'] ?? NULL,
                        "floors" => $propertyInfo['floors'] ?? NULL,
                        "floors_other" => $propertyInfo['floors_other'] ?? NULL,
                        "wall_construction" => $propertyInfo['wall_construction'] ?? NULL,
                        "wall_construction_other" => $propertyInfo['wall_construction_other'] ?? '',
                        "wall_finish" => $propertyInfo['wall_finish'] ?? NULL,
                        "wall_finish_other" => $propertyInfo['wall_finish_other'] ?? NULL,
                        "floors_above" => $propertyInfo['floors_above'] ?? NULL,
                        "floors_above_other" => $propertyInfo['floors_above_other'] ?? NULL,
                        "floors_below" => $propertyInfo['floors_below'] ?? NULL,
                        "floors_below_other" => $propertyInfo['floors_below_other'] ?? NULL,
                    ]);

                    $property->constructionMaterials()->sync($propertyInfo['construction_materials'] ?? NULL);
                    if (isset($propertyInfo['construction_materials']) && in_array(MATERIAL_OTHER, $propertyInfo['construction_materials'])
                        && isset($propertyInfo['construction_material_other'])) {
                        $property->constructionMaterials()
                            ->updateExistingPivot(MATERIAL_OTHER, ['other' => $propertyInfo['construction_material_other']]);
                    }

                    $dataPropertyVulnerable = [
                        'property_id' => $property->id,
                        'vulnerable_occupant_type' => $propertyInfo['vulnerable_occupant_type'] ?? '',
                        'avg_vulnerable_occupants' => $propertyInfo['avg_vulnerable_occupants'],
                        'max_vulnerable_occupants' => $propertyInfo['max_vulnerable_occupants'],
                        'last_vulnerable_occupants' => $propertyInfo['last_vulnerable_occupants'],
                    ];
                    $vulnerableOccupant = $property->vulnerableOccupant;
                    if ($vulnerableOccupant) {
                        $this->vulnerableRepository->update($dataPropertyVulnerable, $vulnerableOccupant->id);
                    } else {
                        $vulnerableOccupant = $this->vulnerableRepository->create($dataPropertyVulnerable);
                    }
//                    $vulnerableOccupant->vulnerableOccupantTypes()->sync($propertyInfo['vulnerable_occupant_type']);

                    // Update Property Image
                    if (\CommonHelpers::checkFile($assessment->id, PROPERTY_ASSESS_IMAGE)) {
                        $file = ShineDocumentStorage::where('object_id', $assessment->id)->where('type', PROPERTY_ASSESS_IMAGE)->first();

                        if (is_file($file->path)) {
                            ShineDocumentStorage::updateOrCreate(
                                ['object_id' => $assessment->property_id, 'type' => PROPERTY_IMAGE],
                                [
                                    'path' => $file->path,
                                    'file_name' => $file->file_name,
                                    'mime' => $file->mime,
                                    'size' => $file->size,
                                    "addedBy" => $file->addedBy ,
                                    'addedDate' => $file->addedDate
                                ]
                            );
                        }
                    }
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }

        return true;
    }

    private function pullFireExitsToRegister($fireExits, $property_id)
    {
        foreach ($fireExits as $fireExit) {
            // check record_id whether exist on register
            if ($register = $this->exitRepository->getByRecordIdAndPropertyId($fireExit->record_id, $property_id)) {
                // update with record_id = old record_id
                try {
                    $register->name = $fireExit->name;
                    $register->type = $fireExit->type;
                    $register->area_id = $this->areaRepository->getRegisterAreaByRecordId($fireExit->area->record_id ?? 0);
                    $register->location_id = $this->locationRepository->getRegisterLocationByRecordId($fireExit->location->record_id ?? 0);
                    $register->accessibility = $fireExit->accessibility;
                    $register->reason_na = $fireExit->reason_na;
                    $register->reason_na_other = $fireExit->reason_na_other;
                    $register->decommissioned = $fireExit->decommissioned;
                    $register->reason_decommissioned = $fireExit->reason_decommissioned;
                    $register->comment = $fireExit->comment;
                    $register->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
                    $register->save();
                    // update document storage
                    $this->pullRelationsToRegister($fireExit, $register, 'fireExistPhotoShineDocumentStorage', 'object_id');
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                // Create new data on register with record_id = assessment's record_id
                try {
                    $register = $this->exitRepository->create([
                        'record_id' => $fireExit->record_id,
                        'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $fireExit->reference,
                        'name' => $fireExit->name,
                        'type' => $fireExit->type,
                        'property_id' => $fireExit->property_id,
                        'assess_id' => 0,
                        'area_id' => $this->areaRepository->getRegisterAreaByRecordId($fireExit->area->record_id ?? 0),
                        'location_id' => $this->locationRepository->getRegisterLocationByRecordId($fireExit->location->record_id ?? 0),
                        'accessibility' => $fireExit->accessibility,
                        'reason_na' => $fireExit->reason_na,
                        'reason_na_other' => $fireExit->reason_na_other,
                        'comment' => $fireExit->comment,
                        'decommissioned' => $fireExit->decommissioned,
                        'reason_decommissioned' => $fireExit->reason_decommissioned,
                    ]);
                    $this->checkMultipleRelations($fireExit->fireExistPhotoShineDocumentStorage, 'object_id', $register->id);
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }
        return true;
    }

    private function pullAssemblyPointsToRegister($assemblyPoints, $property_id)
    {
        foreach ($assemblyPoints as $assemblyPoint) {
            // check record_id whether exist on register
            if ($register = $this->assemblyRepository->getByRecordIdAndPropertyId($assemblyPoint->record_id, $property_id)) {
                // update with record_id = old record_id
                try {
                    $register->name = $assemblyPoint->name;
                    $register->area_id = $this->areaRepository->getRegisterAreaByRecordId($assemblyPoint->area->record_id ?? 0);
                    $register->location_id = $this->locationRepository->getRegisterLocationByRecordId($assemblyPoint->location->record_id ?? 0);
                    $register->accessibility = $assemblyPoint->accessibility;
                    $register->reason_na = $assemblyPoint->reason_na;
                    $register->reason_na_other = $assemblyPoint->reason_na_other;
                    $register->decommissioned = $assemblyPoint->decommissioned;
                    $register->reason_decommissioned = $assemblyPoint->reason_decommissioned;
                    $register->comment = $assemblyPoint->comment;
                    $register->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
                    $register->save();
                    // update document storage
                    $this->pullRelationsToRegister($assemblyPoint, $register, 'assemblyPointPhotoShineDocumentStorage', 'object_id');
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                // Create new data on register with record_id = assessment's record_id
                try {
                    $register = $this->assemblyRepository->create([
                        'record_id' => $assemblyPoint->record_id,
                        'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $assemblyPoint->reference,
                        'name' => $assemblyPoint->name,
                        'property_id' => $assemblyPoint->property_id,
                        'assess_id' => 0,
                        'area_id' => $this->areaRepository->getRegisterAreaByRecordId($assemblyPoint->area->record_id ?? 0),
                        'location_id' => $this->locationRepository->getRegisterLocationByRecordId($assemblyPoint->location->record_id ?? 0),
                        'accessibility' => $assemblyPoint->accessibility,
                        'reason_na' => $assemblyPoint->reason_na,
                        'reason_na_other' => $assemblyPoint->reason_na_other,
                        'comment' => $assemblyPoint->comment,
                        'decommissioned' => $assemblyPoint->decommissioned,
                        'reason_decommissioned' => $assemblyPoint->reason_decommissioned,
                    ]);
                    $this->checkMultipleRelations($assemblyPoint->assemblyPointPhotoShineDocumentStorage, 'object_id', $register->id);
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }
        return true;
    }

    private function pullVehicleParkingToRegister($vehicleParking, $property_id)
    {
        foreach ($vehicleParking as $parking) {
            // check record_id whether exist on register
            if ($register = $this->vehicleParkingRepository->getByRecordIdAndPropertyId($parking->record_id, $property_id)) {
                // update with record_id = old record_id
                try {
                    $register->name = $parking->name;
                    $register->area_id = $this->areaRepository->getRegisterAreaByRecordId($parking->area->record_id ?? 0);
                    $register->location_id = $this->locationRepository->getRegisterLocationByRecordId($parking->location->record_id ?? 0);
                    $register->accessibility = $parking->accessibility;
                    $register->reason_na = $parking->reason_na;
                    $register->reason_na_other = $parking->reason_na_other;
                    $register->decommissioned = $parking->decommissioned;
                    $register->reason_decommissioned = $parking->reason_decommissioned;
                    $register->comment = $parking->comment;
                    $register->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
                    $register->save();
                    // update document storage
                    $this->pullRelationsToRegister($parking, $register, 'vehicleParkingPhotoShineDocumentStorage', 'object_id');
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                // Create new data on register with record_id = assessment's record_id
                try {
                    $register = $this->vehicleParkingRepository->create([
                        'record_id' => $parking->record_id,
                        'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $parking->reference,
                        'name' => $parking->name,
                        'property_id' => $parking->property_id,
                        'assess_id' => 0,
                        'area_id' => $this->areaRepository->getRegisterAreaByRecordId($parking->area->record_id ?? 0),
                        'location_id' => $this->locationRepository->getRegisterLocationByRecordId($parking->location->record_id ?? 0),
                        'accessibility' => $parking->accessibility,
                        'reason_na' => $parking->reason_na,
                        'reason_na_other' => $parking->reason_na_other,
                        'comment' => $parking->comment,
                        'decommissioned' => $parking->decommissioned,
                        'reason_decommissioned' => $parking->reason_decommissioned,
                    ]);
                    $this->checkMultipleRelations($parking->vehicleParkingPhotoShineDocumentStorage, 'object_id', $register->id);
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }
        return true;
    }

    private function pullSystemsToRegister($systems, $property_id)
    {
        foreach ($systems as $system) {
            // check record_id whether exist on register
            if ($register = $this->systemRepository->getByRecordIdAndPropertyId($system->record_id, $property_id)) {
                try {
                    // update with record_id = old record_id
                    $register->name = $system->name;
                    $register->type = $system->type;
                    $register->classification = $system->classification;
                    $register->decommissioned = $system->decommissioned;
                    $register->comment = $system->comment;
                    $register->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
                    $register->updated_by = $system->updated_by;
                    $register->save();
                    // update document storage
                    $this->pullRelationsToRegister($system, $register, 'complianceDocumentStorage', 'object_id');
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                // Create new data on register with record_id = assessment's record_id
                try {
                    $register = $this->systemRepository->create([
                        'record_id' => $system->record_id,
                        'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $system->reference,
                        'name' => $system->name,
                        'property_id' => $system->property_id,
                        'assess_id' => 0,
                        'type' => $system->type,
                        'classification' => $system->classification,
                        'comment' => $system->comment,
                        'decommissioned' => $system->decommissioned,
                        'created_by' => $system->created_by,
                    ]);
                    $this->checkMultipleRelations($system->complianceDocumentStorage, 'object_id', $register->id);
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }

            }
        }
        return true;
    }

    private function pullEquipmentsToRegister($equipments, $property_id)
    {
        $register_equipments = [];
        foreach ($equipments as $equipment) {
            // check record_id whether exist on register
            if ($register = $this->equipmentRepository->getByRecordIdAndPropertyId($equipment->record_id, $property_id)) {
                // update with record_id = old record_id
                try {
                    $register->name = $equipment->name;
                    $register->area_id = $this->areaRepository->getRegisterAreaByRecordId($equipment->area->record_id ?? 0);
                    $register->location_id = $this->locationRepository->getRegisterLocationByRecordId($equipment->location->record_id ?? 0);
                    $register->state = $equipment->state;
                    $register->reason = $equipment->reason;
                    $register->reason_other = $equipment->reason_other;
                    $register->parent_id = $equipment->parent_id;
                    $register->system_id = $this->systemRepository->getRegisterByRecordId($equipment->system->record_id ?? 0);
                    $register->frequency_use = $equipment->frequency_use;
                    $register->extent = $equipment->extent;
                    $register->operational_use = $equipment->operational_use;
                    $register->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
                    $register->decommissioned = $equipment->decommissioned;
                    $register->save();
                    // update document storage
                    $this->pullRelationsToRegister($equipment, $register, 'equipmentPhotoshineDocumentStorage', 'object_id');
                    $this->pullRelationsToRegister($equipment, $register, 'equipmentLocationPhotoshineDocumentStorage', 'object_id');
                    $this->pullRelationsToRegister($equipment, $register, 'equipmentAdditionalPhotoshineDocumentStorage', 'object_id');
                    // Equipment details
                    $this->pullRelationsToRegister($equipment, $register, 'equipmentConstruction', 'equipment_id');
                    $this->pullRelationsToRegister($equipment, $register, 'cleaning', 'equipment_id');
                    $this->pullRelationsToRegister($equipment, $register, 'tempAndPh', 'equipment_id');
                    $this->pullRelationsToRegister($equipment, $register, 'equipmentModel', 'equipment_id');
                    $this->pullRelationsToRegister($equipment, $register, 'specificLocationValue', 'equipment_id');

                      //log temperature
                      $data_temp = [
                        'return_temp' => $equipment->tempAndPh->return_temp ?? null,
                        'flow_temp' => $equipment->tempAndPh->flow_temp ?? null,
                        'inlet_temp' => $equipment->tempAndPh->inlet_temp ?? null,
                        'stored_temp' => $equipment->tempAndPh->stored_temp ?? null,
                        'top_temp' => $equipment->tempAndPh->top_temp ?? null,
                        'bottom_temp' => $equipment->tempAndPh->bottom_temp ?? null,
                        'flow_temp_gauge_value' => $equipment->tempAndPh->flow_temp_gauge_value ?? null,
                        'return_temp_gauge_value' => $equipment->tempAndPh->return_temp_gauge_value ?? null,
                        'ambient_area_temp' => $equipment->tempAndPh->ambient_area_temp ?? null,
                        'incoming_main_pipe_work_temp' => $equipment->tempAndPh->incoming_main_pipe_work_temp ?? null,
                        'cold_flow_temp' => $equipment->tempAndPh->cold_flow_temp ?? null,
                        'hot_flow_temp' => $equipment->tempAndPh->hot_flow_temp ?? null,
                        'pre_tmv_cold_flow_temp' => $equipment->tempAndPh->pre_tmv_cold_flow_temp ?? null,
                        'pre_tmv_hot_flow_temp' => $equipment->tempAndPh->pre_tmv_hot_flow_temp ?? null,
                        'post_tmv_temp' => $equipment->tempAndPh->post_tmv_temp ?? null,
                        'ph' => $equipment->tempAndPh->ph ?? null
                    ];

                    $data_log = $data_temp;
                    $data_log['created_by'] = $equipment->updated_by;
                    $data_log['assess_id'] = $equipment->assess_id;
                    $data_log['equipment_id'] = $register->id;
                    $this->tempLogRepository->create($data_log);

                    $register_equipments[] = $register;
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                // Create new data on register with record_id = assessment's record_id
                try {
                    $register = $this->equipmentRepository->create([
                        'record_id' => $equipment->record_id,
                        'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $equipment->reference,
                        'name' => $equipment->name,
                        'property_id' => $equipment->property_id,
                        'assess_id' => 0,
                        'area_id' => $this->areaRepository->getRegisterAreaByRecordId($equipment->area->record_id ?? 0),
                        'location_id' => $this->locationRepository->getRegisterLocationByRecordId($equipment->location->record_id ?? 0),
                        'type' => $equipment->type,
                        'state' => $equipment->state,
                        'reason' => $equipment->reason,
                        'reason_other' => $equipment->reason_other,
                        'parent_id' => $equipment->parent_id,
                        'hot_parent_id' => $equipment->hot_parent_id,
                        'cold_parent_id' => $equipment->cold_parent_id,
                        'system_id' => $equipment->system_id,
                        'frequency_use' => $equipment->frequency_use,
                        'extent' => $equipment->extent,
                        'operational_use' => $equipment->operational_use,
                        'decommissioned' => $equipment->decommissioned,
                        'created_by' => $equipment->created_by,
                    ]);

                    $this->checkMultipleRelations($equipment->equipmentPhotoshineDocumentStorage, 'object_id', $register->id);
                    $this->checkMultipleRelations($equipment->equipmentLocationPhotoshineDocumentStorage, 'object_id', $register->id);
                    $this->checkMultipleRelations($equipment->equipmentAdditionalPhotoshineDocumentStorage, 'object_id', $register->id);
                    $this->checkMultipleRelations($equipment->equipmentConstruction, 'equipment_id', $register->id);
                    $this->checkMultipleRelations($equipment->cleaning, 'equipment_id', $register->id);
                    $this->checkMultipleRelations($equipment->tempAndPh, 'equipment_id', $register->id);
                    $this->checkMultipleRelations($equipment->equipmentModel, 'equipment_id', $register->id);
                    $this->checkMultipleRelations($equipment->specificLocationValue, 'equipment_id', $register->id);

                    //log temperature

                    $data_temp = [
                        'return_temp' => $equipment->tempAndPh->return_temp ?? null,
                        'flow_temp' => $equipment->tempAndPh->flow_temp ?? null,
                        'inlet_temp' => $equipment->tempAndPh->inlet_temp ?? null,
                        'stored_temp' => $equipment->tempAndPh->stored_temp ?? null,
                        'top_temp' => $equipment->tempAndPh->top_temp ?? null,
                        'bottom_temp' => $equipment->tempAndPh->bottom_temp ?? null,
                        'flow_temp_gauge_value' => $equipment->tempAndPh->flow_temp_gauge_value ?? null,
                        'return_temp_gauge_value' => $equipment->tempAndPh->return_temp_gauge_value ?? null,
                        'ambient_area_temp' => $equipment->tempAndPh->ambient_area_temp ?? null,
                        'incoming_main_pipe_work_temp' => $equipment->tempAndPh->incoming_main_pipe_work_temp ?? null,
                        'cold_flow_temp' => $equipment->tempAndPh->cold_flow_temp ?? null,
                        'hot_flow_temp' => $equipment->tempAndPh->hot_flow_temp ?? null,
                        'pre_tmv_cold_flow_temp' => $equipment->tempAndPh->pre_tmv_cold_flow_temp ?? null,
                        'pre_tmv_hot_flow_temp' => $equipment->tempAndPh->pre_tmv_hot_flow_temp ?? null,
                        'post_tmv_temp' => $equipment->tempAndPh->post_tmv_temp ?? null,
                        'ph' => $equipment->tempAndPh->ph ?? null
                    ];
                    $data_log = $data_temp;
                    $data_log['created_by'] = $equipment->updated_by;
                    $data_log['assess_id'] = $equipment->assess_id;
                    $data_log['equipment_id'] = $register->id;
                    $this->tempLogRepository->create($data_log);

                    $register_equipments[] = $register;
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }
        if(count($register_equipments)){
            foreach ($register_equipments as $equipment) {
                if (!$this->correctPulledEquipmentParentId($equipment)) {
                    return false;
                }
            }
        }
        return true;
    }

    private function pullHazardsToRegister($hazards, $property_id)
    {
        foreach ($hazards as $hazard) {
            // check record_id whether exist on register
            if ($register = $this->hazardRepository->getByRecordIdAndPropertyId($hazard->record_id, $property_id)) {
                // update with record_id = old record_id
                try {
                    $register->name = $hazard->name;
                    $register->area_id = $this->areaRepository->getRegisterAreaByRecordId($hazard->area->record_id ?? 0);
                    $register->location_id = $this->locationRepository->getRegisterLocationByRecordId($hazard->location->record_id ?? 0);
                    $register->decommissioned = $hazard->decommissioned;
                    $register->decommissioned_reason = $hazard->decommissioned_reason;
                    $register->reason = $hazard->reason;
                    $register->reason_other = $hazard->reason_other;
                    $register->assess_type = $hazard->assess_type;
                    $register->created_date = $hazard->created_date;
                    $register->type = $hazard->type;
                    $register->likelihood_of_harm = $hazard->likelihood_of_harm;
                    $register->hazard_potential = $hazard->hazard_potential;
                    $register->total_risk = $hazard->total_risk;
                    $register->extent = $hazard->extent;
                    $register->measure_id = $hazard->measure_id;
                    $register->photo_override = $hazard->photo_override;
                    $register->act_recommendation_verb_other = $hazard->act_recommendation_verb_other;
                    $register->act_recommendation_verb = $hazard->act_recommendation_verb;
                    $register->act_recommendation_noun_other = $hazard->act_recommendation_noun_other;
                    $register->act_recommendation_noun = $hazard->act_recommendation_noun;
                    $register->action_responsibility = $hazard->action_responsibility;
                    $register->linked_question_id = $hazard->linked_question_id;
                    $register->linked_project_id = $hazard->linked_project_id;
                    $register->comment = $hazard->comment;
                    $register->is_deleted = $hazard->is_deleted;
                    $register->is_locked = COMPLIANCE_ASSESSMENT_UNLOCKED;
                    $register->save();
                    // update document storage
                    $this->pullRelationsToRegister($hazard, $register, 'hazardPhotoshineDocumentStorage', 'object_id');
                    $this->pullRelationsToRegister($hazard, $register, 'hazardLocationPhotoshineDocumentStorage', 'object_id');
                    $this->pullRelationsToRegister($hazard, $register, 'hazardAdditionalPhotoshineDocumentStorage', 'object_id');
                    // update specific location
                    $this->pullRelationsToRegister($hazard, $register, 'hazardSpecificLocationValue', 'hazard_id');
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                // Create new data on register with record_id = assessment's record_id
                if ($hazard->is_temp || $hazard->is_delete) {
                    continue;
                }
                try {
                    $register = $this->hazardRepository->create([
                        'record_id' => $hazard->record_id,
                        'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $hazard->reference,
                        'name' => $hazard->name,
                        'property_id' => $hazard->property_id,
                        'assess_id' => 0,
                        'area_id' => $this->areaRepository->getRegisterAreaByRecordId($hazard->area->record_id ?? 0),
                        'location_id' => $this->locationRepository->getRegisterLocationByRecordId($hazard->location->record_id ?? 0),
                        'accessibility' => $hazard->accessibility,
                        'assess_type' => $hazard->assess_type,
                        'created_date' => $hazard->created_date,
                        'decommissioned_reason' => $hazard->decommissioned_reason,
                        'reason' => $hazard->reason,
                        'reason_other' => $hazard->reason_other,
                        'comment' => $hazard->comment,
                        'decommissioned' => $hazard->decommissioned,
                        'likelihood_of_harm' => $hazard->likelihood_of_harm,
                        'hazard_potential' => $hazard->hazard_potential,
                        'total_risk' => $hazard->total_risk,
                        'extent' => $hazard->extent,
                        'type' => $hazard->type,
                        'measure_id' => $hazard->measure_id,
                        'photo_override' => $hazard->photo_override,
                        'act_recommendation_verb_other' => $hazard->act_recommendation_verb_other,
                        'act_recommendation_verb' => $hazard->act_recommendation_verb,
                        'act_recommendation_noun_other' => $hazard->act_recommendation_noun_other,
                        'act_recommendation_noun' => $hazard->act_recommendation_noun,
                        'action_responsibility' => $hazard->action_responsibility,
                        'linked_question_id' => $hazard->linked_question_id,
                        'linked_project_id' => $hazard->linked_project_id,
                        'is_deleted' => $hazard->is_deleted,
                    ]);
                    $this->checkMultipleRelations($hazard->hazardPhotoshineDocumentStorage, 'object_id', $register->id);
                    $this->checkMultipleRelations($hazard->hazardLocationPhotoshineDocumentStorage, 'object_id', $register->id);
                    $this->checkMultipleRelations($hazard->hazardAdditionalPhotoshineDocumentStorage, 'object_id', $register->id);
                    $this->checkMultipleRelations($hazard->hazardSpecificLocationValue, 'hazard_id', $register->id);
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }
        return true;
    }

    private function pullNonconformitesToRegister($nonconformities, $property_id)
    {
        foreach ($nonconformities as $nonconformity) {
            // check record_id whether exist on register
            if ($register = $this->nonconformityRepository->getByRecordIdAndPropertyId($nonconformity->record_id, $property_id)) {
                // update with record_id = old record_id
                try {
                    $register->equipment_id = $this->equipmentRepository->getRegisterByRecordId($nonconformity->equipment->record_id ?? 0);
                    $register->hazard_id = $this->hazardRepository->getRegisterByRecordId($nonconformity->hazard->record_id ?? 0);
                    $register->field = $nonconformity->field;
                    $register->type = $nonconformity->type;
                    $register->updated_by = $nonconformity->updated_by;
                    $register->is_deleted = $nonconformity->is_deleted;
               // $register->is_locked = $nonconformity->is_locked;
                    $register->save();
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            } else {
                if ($nonconformity->is_deleted) {
                    continue;
                }
                // Create new data on register with record_id = assessment's record_id
                try {
                    $register = $this->nonconformityRepository->create([
                        'record_id' => $nonconformity->record_id,
                   // 'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
                        'reference' => $nonconformity->reference,
                        'property_id' => $nonconformity->property_id,
                        'assess_id' => 0,
                        'equipment_id' => $this->equipmentRepository->getRegisterByRecordId($nonconformity->equipment->record_id ?? 0),
                        'hazard_id' => $this->hazardRepository->getRegisterByRecordId($nonconformity->hazard->record_id ?? 0),
                        'field' => $nonconformity->field,
                        'type' => $nonconformity->type,
                        'created_by' => $nonconformity->created_by,
                    ]);
                } catch (\Exception $exception) {
                    Log::error($exception);
                    return false;
                }
            }
        }
        return true;
    }

    private function correctPulledEquipmentParentId($equipment)
    {
        try {
            $equipment->system_id = $this->systemRepository->getRegisterByRecordId($equipment->system->record_id ?? 0);
            $equipment->parent_id = $this->equipmentRepository->getRegisterByRecordId($equipment->parent->record_id ?? 0);
            $equipment->hot_parent_id = $this->equipmentRepository->getRegisterByRecordId($equipment->hotParent->record_id ?? 0);
            $equipment->cold_parent_id = $this->equipmentRepository->getRegisterByRecordId($equipment->coldParent->record_id ?? 0);
            $equipment->save();
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
        return true;
    }

    private function pullRelationsToRegister($assessItem, $register, $relation, $foreignKey)
    {
        try {
            if ($assessItem->{$relation}) {
                if ($register->{$relation}) {
                    $register->{$relation}->fill($assessItem->{$relation}->toArray());
                    $register->{$relation}->{$foreignKey} = $register->id;
                    $register->{$relation}->save();
                } else {
                    $register->{$relation} = $assessItem->{$relation}->replicate();
                    $register->{$relation}->{$foreignKey} = $register->id;
                    $register->{$relation}->save();
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    private function lockAssessment($assess_id)
    {
        try {
            $this->hazardRepository->lockHazardsByAssessmentId($assess_id);
            $this->equipmentRepository->lockEquipmentsByAssessmentId($assess_id);
            $this->systemRepository->lockSystemsByAssessmentId($assess_id);
            $this->vehicleParkingRepository->lockVehicleParkingByAssessmentId($assess_id);
            $this->exitRepository->lockExitsByAssessmentId($assess_id);
            $this->assemblyRepository->lockAssemblyPointsByAssessmentId($assess_id);
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    private function unlockAssessment($assess_id)
    {
        // Lock all items of Assessment
        try {
            $this->hazardRepository->unlockHazardsByAssessmentId($assess_id);
            $this->equipmentRepository->unlockEquipmentsByAssessmentId($assess_id);
            $this->systemRepository->unlockSystemsByAssessmentId($assess_id);
            $this->vehicleParkingRepository->unlockVehicleParkingByAssessmentId($assess_id);
            $this->exitRepository->unlockExitsByAssessmentId($assess_id);
            $this->assemblyRepository->unlockAssemblyPointsByAssessmentId($assess_id);

            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
        }

        return false;
    }

    public function getRejectedAssessments($user_id = null)
    {
        if (\CommonHelpers::isSystemClient()) {
            // property privilege

             return $this->assessmentRepository->getRejectedAssessments($user_id);
        } else {
            $query = $this->assessmentRepository->where('status', ASSESSMENT_STATUS_REJECTED)
                ->where('decommissioned', SURVEY_UNDECOMMISSION)
                ->where('client_id', \Auth::user()->client_id);
            if(!empty($user_id)) {
                $query = $query->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    // $query->orWhere(['quality_id' => $user_id]);
                });
            }
            return $query->get();
        }
    }

    public function updateOrCreateSampling($data, $id = null) {
        if (!empty($data)) {
            // SitePlanDocument
            $dataSample = [
                "assess_id" => $data['assess_id'] ?? 0,
                "sample_reference" => $data['sample_reference'] ?? 0,
                "date" => \CommonHelpers::toTimeStamp( $data['date'] ?? 0),
                "description" => $data['description'] ?? '',
                "property_id" => $data['property_id'] ?? '',
            ];

            try {
                \DB::beginTransaction();
                if (is_null($id)) {
                        $assess_sample = $this->assessmentSamplingRepository->createSampling($dataSample);
                    if ($assess_sample) {
                        $samplRef = [
                            'reference' => "SC" . $assess_sample->id
                        ];
                            $this->assessmentSamplingRepository->updateSampling($assess_sample->id,$samplRef);
                        }
                        //log audit
                        $comment = \Auth::user()->full_name . " add sample certificate " . $assess_sample->name;
                    \CommonHelpers::logAudit(SAMPLE_CERTIFICATE, $assess_sample->id, AUDIT_ACTION_ADD, $assess_sample->reference, $dataSample['assess_id'], $comment, 0 , $dataSample['property_id']);
                    $response = \CommonHelpers::successResponse('Upload plan document successfully !');
                } else {

                    $this->assessmentSamplingRepository->updateSampling($id,$dataSample);
                    $assess_sample = $this->assessmentSamplingRepository->getFirstAssessmentSampling($id);
                    $response = \CommonHelpers::successResponse('Update plan document successfully !');

                    //log audit
                    $comment = \Auth::user()->full_name . " edited plan " . $assess_sample->name;
                    \CommonHelpers::logAudit(SAMPLE_CERTIFICATE, $assess_sample->id, AUDIT_ACTION_EDIT, $assess_sample->reference, $dataSample['assess_id'], $comment, 0 , $dataSample['property_id']);
                }

                if (isset($data['document'])) {
                    $saveLocationImage = \CommonHelpers::saveFileShineDocumentStorage($data['document'], $assess_sample->id, SAMPLE_CERTIFICATE_ASSESSMENT,\CommonHelpers::checkArrayKey3($data,'assess_id'));
                    $dataUpdateImg = [
                        'document_present' => 1,
                    ];
                        $this->assessmentSamplingRepository->updateSampling($assess_sample->id,$dataUpdateImg);
                }
                \DB::commit();
                return $response;
            } catch (\Exception $e) {
                Log::error($e);
                \DB::rollback();
                return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload plan document. Please try again !');
            }
        }
    }

    public function getSampling($assess_id){
        $dataSample = $this->assessmentSamplingRepository->getAssessmentSample($assess_id);
        return $dataSample ?? [];
    }

    public function getAssessmentTypes($classification)
    {
        $assessmentTypes = [];

        switch ($classification){
            case ASBESTOS:
            case GAS:
                // nothing
                $assessmentTypes = [];
                break;
            case FIRE:
                $assessmentTypes = [
                    (object)['key' => ASSESS_TYPE_FIRE_EQUIPMENT, 'value' => 'Fire Equipment Assessment'],
                    (object)['key' => ASSESS_TYPE_FIRE_RISK_TYPE_1, 'value' => 'Fire Risk Assessment (Type 1)'],
                    (object)['key' => ASSESS_TYPE_FIRE_RISK_TYPE_2, 'value' => 'Fire Risk Assessment (Type 2)'],
                    (object)['key' => ASSESS_TYPE_FIRE_RISK_TYPE_3, 'value' => 'Fire Risk Assessment (Type 3)'],
                    (object)['key' => ASSESS_TYPE_FIRE_RISK_TYPE_4, 'value' => 'Fire Risk Assessment (Type 4)'],
                ];
                break;
            case WATER:
                $assessmentTypes = [
                    (object)['key' => ASSESS_TYPE_WATER_EQUIPMENT, 'value' => 'Water Equipment Assessment'],
                    (object)['key' => ASSESS_TYPE_WATER_RISK, 'value' => 'Water Risk Assessment'],
                    (object)['key' => ASSESS_TYPE_WATER_TEMP, 'value' => 'Water Temperature Assessment'],
                ];
                break;

            case HS:
                $assessmentTypes = [
                    (object)['key' => ASSESS_TYPE_HS, 'value' => 'Health and Safety Assessment'],
                ];
                break;
        }

        return $assessmentTypes;
    }

    public function updateFireSafety($data)
    {
        try {
            $assessment = $this->getAssessmentDetail($data['assess_id']);

            if ($assessment && $assessment->assessmentInfo) {
                $assessmentInfo = $assessment->assessmentInfo;
                $assessmentInfo->fire_safety = implode(',',$data['fire_safety']) ?? null;
                $assessmentInfo->fire_safety_other = $data['fire_safety-other'] ?? null;
                $assessmentInfo->save();
            }

            return \CommonHelpers::successResponse('Update Fire Safety Equipment & System successfully !');
        } catch (\Exception $e) {
            Log::error($e);
            return \CommonHelpers::failResponse(STATUS_FAIL,'Failed to upload Fire Safety Equipment & System. Please try again !');
        }

    }

    public function getOtherHazardAnswers($assessment_id = null) {
        return AssessmentValue::where(['assess_id' => $assessment_id, 'question_id' => OTHER_HAZARD_IDENTIFIED_QUESTION_ID])->get();
    }
}
