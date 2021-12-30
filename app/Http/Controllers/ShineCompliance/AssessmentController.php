<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Request\ShineCompliance\Assessment\AssemblyPointRequest;
use App\Http\Request\ShineCompliance\Assessment\CreateAssessmentRequest;
use App\Http\Request\ShineCompliance\Assessment\EditAssessmentRequest;
use App\Http\Request\ShineCompliance\Assessment\FireExitRequest;
use App\Http\Request\ShineCompliance\Assessment\VehicleParkingRequest;
use App\Models\ShineCompliance\AssessmentInfo;
use App\Models\ShineCompliance\AssessmentQuestion;
use App\Models\ShineCompliance\AssessmentValue;
use App\Models\ShineCompliance\PropertySurvey;
use App\Services\ShineCompliance\AreaService;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\HazardService;
use App\Services\ShineCompliance\ItemService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\EquipmentService;
use App\Services\ShineCompliance\SurveyService;
use App\Services\ShineCompliance\ProjectService;
use App\Services\ShineCompliance\SystemService;
use App\Services\ShineCompliance\GenerateAssessmentPDFService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AssessmentController extends Controller
{
    private $assessmentService;
    private $propertyService;
    private $itemService;
    private $areaService;
    private $equipmentService;
    private $surveyService;
    private $projectService;
    private $clientService;
    private $systemService;
    private $generateAssessmentPDFService;
    private $hazardService;

    public function __construct(AssessmentService $assessmentService,
        PropertyService $propertyService,
        EquipmentService $equipmentService,
        SurveyService $surveyService,
        ProjectService $projectService,
        ItemService $itemService,
        ClientService $clientService,
        GenerateAssessmentPDFService $generateAssessmentPDFService,
        AreaService $areaService,
        SystemService $systemService,
        HazardService $hazardService)
    {
        $this->assessmentService = $assessmentService;
        $this->propertyService = $propertyService;
        $this->itemService = $itemService;
        $this->areaService = $areaService;
        $this->equipmentService = $equipmentService;
        $this->surveyService = $surveyService;
        $this->projectService = $projectService;
        $this->systemService = $systemService;
        $this->generateAssessmentPDFService = $generateAssessmentPDFService;
        $this->hazardService = $hazardService;
        $this->clientService = $clientService;
    }

    public function index($property_id)
    {
        if (!$property = $this->propertyService->getProperty($property_id)) {
            abort(404);
        }
        $canAddFireAssessment = true;
        $canAddWaterAssessment = true;
        $canAddHSAssessment = true;
        $canAddSurvey = true;
        if (!\CommonHelpers::isSystemClient()) {
            $canAddFireAssessment = $this->assessmentService->isWinnerSurveyContractor($property_id, \Auth::user()->client_id, ASSESSMENT_FIRE_TYPE);
            $canAddWaterAssessment = $this->assessmentService->isWinnerSurveyContractor($property_id, \Auth::user()->client_id, ASSESSMENT_WATER_TYPE);
            $canAddHSAssessment = $this->assessmentService->isWinnerSurveyContractor($property_id, \Auth::user()->client_id, ASSESSMENT_HS_TYPE);
            $canAddSurvey = $this->assessmentService->isWinnerSurveyContractor($property_id, \Auth::user()->client_id, ASSESSMENT_ASBESTOS_TYPE);
        } else {
            $edit_property_permission = \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id);
            // check update permission for asbestos
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_ASBESTOS,JOB_ROLE_ASBESTOS ) || !$edit_property_permission) {
                $canAddSurvey = false;
            }
            // check update permission for fire
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_FIRE,JOB_ROLE_FIRE ) || !$edit_property_permission) {
                $canAddFireAssessment = false;
            }
            // check update permission for water
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_WATER,JOB_ROLE_WATER ) || !$edit_property_permission) {
                $canAddWaterAssessment = false;
            }
            // check update permission for water
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_HS,JOB_ROLE_H_S ) || !$edit_property_permission) {
                $canAddHSAssessment = false;
            }

        }
        $canAddAssessment = true; // TODO: check permission
        $fireAssessments = $this->assessmentService->getAssessments($property_id, ASSESSMENT_FIRE_TYPE);
        $fireAssessmentsDecommissioned = $this->assessmentService->getAssessments($property_id, ASSESSMENT_FIRE_TYPE, DECOMMISSION);
        $waterAssessments = $this->assessmentService->getAssessments($property_id, ASSESSMENT_WATER_TYPE);
        $waterAssessmentsDecommissioned = $this->assessmentService->getAssessments($property_id, ASSESSMENT_WATER_TYPE, DECOMMISSION);
        $hsAssessments = $this->assessmentService->getAssessments($property_id, ASSESSMENT_HS_TYPE);
        $hsAssessmentsDecommissioned = $this->assessmentService->getAssessments($property_id, ASSESSMENT_HS_TYPE, DECOMMISSION);
        $asbestosAssessments = $this->surveyService->getAllSurveyByProperty($property_id);
        $asbestosAssessmentsDecommissioned = $this->surveyService->getAllSurveyByProperty($property_id, DECOMMISSION);

        $management_Survey = $asbestosAssessments->where('survey_type',MANAGEMENT_SURVEY);

        $management_Survey_partial = $asbestosAssessments->where('survey_type',MANAGEMENT_SURVEY_PARTIAL);
        $refurbishment_Survey = $asbestosAssessments->where('survey_type',REFURBISHMENT_SURVEY);
        $reInspection_Survey= $asbestosAssessments->where('survey_type',RE_INSPECTION_REPORT);
        $sample_Survey= $asbestosAssessments->where('survey_type',SAMPLE_SURVEY);
        $demolition_Survey = $asbestosAssessments->where('survey_type',DEMOLITION_SURVEY);
        $fra = $fireAssessments->where('type',ASSESS_TYPE_FIRE_EQUIPMENT);
        $fra1 = $fireAssessments->where('type',ASSESS_TYPE_FIRE_RISK_TYPE_1);
        $fra2 = $fireAssessments->where('type',ASSESS_TYPE_FIRE_RISK_TYPE_2);
        $fra3 = $fireAssessments->where('type',ASSESS_TYPE_FIRE_RISK_TYPE_3);
        $fra4 = $fireAssessments->where('type',ASSESS_TYPE_FIRE_RISK_TYPE_4);
        $water_equipment_assessments = $waterAssessments->where('type', ASSESS_TYPE_WATER_EQUIPMENT);
        $water_risk_assessments = $waterAssessments->where('type', ASSESS_TYPE_WATER_RISK);
        $water_temperature_assessments = $waterAssessments->where('type', ASSESS_TYPE_WATER_TEMP);

        $asbestos = true;
        $fire = true;
        $water = true;
        $hs = true;
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_ASBESTOS, JOB_ROLE_ASBESTOS)){
            $asbestos = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_FIRE, JOB_ROLE_FIRE)){
            $fire = false;
        }
        if((\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENTS_WATER, JOB_ROLE_WATER)) || !env('WATER_MODULE')){
            $water = false;
        }

       if((\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENTS_HS, JOB_ROLE_H_S))){
           $hs = false;
       }

        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment list on property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);

        return view('shineCompliance.assessment.index', compact('property',
                              'canAddWaterAssessment',
                                        'canAddSurvey',
                                        'canAddFireAssessment',
                                        'fireAssessments',
                                        'hsAssessments',
                                        'hsAssessmentsDecommissioned',
                                        'asbestos',
                                        'fire',
                                        'water',
                                        'hs',
                                        'fireAssessmentsDecommissioned',
                                        'waterAssessments',
                                        'waterAssessmentsDecommissioned',
                                        'asbestosAssessments',
                                        'asbestosAssessmentsDecommissioned','management_Survey','refurbishment_Survey','reInspection_Survey','sample_Survey','demolition_Survey','management_Survey_partial',
                                        'fra','fra1','fra2','fra3','fra4',
                                        'water_equipment_assessments',
                                        'water_risk_assessments',
                                        'water_temperature_assessments'
        ));
    }

    public function show($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id, [
            'unDecommissionHazard.area',
            'unDecommissionHazard.hazardType',
            'unDecommissionHazard.location',
            'decommissionHazard.area',
            'decommissionHazard.location',
            'decommissionHazard.hazardType',
            'assessmentNonconformities',
            'assessmentNonconformities.equipment',
            'assessmentNonconformities.equipment.equipmentType',
            'equipments',
            'equipments.area',
            'equipments.location',
            'decommissionEquipments',
            'fireExits',
            'decommissionFireExits',
            'assemblyPoints',
            'decommissionAssemblyPoints',
            'vehicleParking',
            'decommissionVehicleParking',
            ])){
            abort(404);
        }
        $property_id = $assessment->property_id ?? 0;
        $plans = $this->assessmentService->getAssessmentPlans($property_id, $assess_id);
        $assessorsNotes = $this->assessmentService->getAssessmentNotes($property_id, $assess_id);
        $sections = $this->assessmentService->getQuestionnaire($assess_id, $assessment->classification);

        $managementInfoQueries = $this->assessmentService->getManagementInfoQueries($assess_id);
        $otherInfoQueries = $this->assessmentService->getOtherInfoQueries($assess_id);
        $fireSafetyAnswer = $this->assessmentService->getAllFireSafetyAnswers();
        $active_tab = $request->active_tab ?? '';
        $asbestos_items = $this->assessmentService->getAsbestosItem($assessment->property_id);
        $samples = $this->assessmentService->getSampling($assess_id);
        $canBeUpdateSurvey = true;

        if (\CommonHelpers::isSystemClient()) {
            if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
                // check update permission for fire
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_FIRE,JOB_ROLE_FIRE )) {
                    $canBeUpdateSurvey = false;
                }
            }

            if ($assessment->classification == ASSESSMENT_WATER_TYPE) {
                // check update permission for water
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_ASSESSMENT_WATER,JOB_ROLE_WATER )) {
                    $canBeUpdateSurvey = false;
                }
            }
        }

        // get other hazard identified answers
        $other_hazard_answers = $this->assessmentService->getOtherHazardAnswers($assess_id);

        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment detail on property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);
        return view('shineCompliance.assessment.show', compact('assessment',
            'active_tab',
            'sections',
            'managementInfoQueries',
            'otherInfoQueries',
            'fireSafetyAnswer',
            'canBeUpdateSurvey',
            'plans',
            'assessorsNotes',
            'asbestos_items',
            'other_hazard_answers',
            'samples'));
    }

    public function getAdd($classification, Request $request)
    {
        $property_id = $request->get('property_id');

        if (!$property_id && !($this->propertyService->getProperty($property_id))) {
            return abort(404);
        }

        $property = $this->propertyService->getProperty($property_id);
        $assessmentTypes = $this->assessmentService->getAssessmentTypes($classification);
        $leads = $this->assessmentService->getLeadUsers();
        $users = $this->assessmentService->getAssessors();
        $clients = $this->clientService->getAllClients();
//        $projects = $this->projectService->getSurveyProjects($property_id);
        $projects = $this->assessmentService->getSurveyProjects($property_id, $classification);
        //register data branch
        $systems = $this->systemService->listRegisterSystemProperty($property_id, ['registerEquipments']);
        $areas = $this->areaService->listRegisterAreaProperty($property_id, [
            'locations.registerEquipments.nonconformities.hazard' => function($query) use($classification) {
                $query->where('assess_type', $this->getAssessType($classification));
            },
            'locations.normalHazard' => function($query) use($classification) {
                $query->where('assess_type', $this->getAssessType($classification));
            },
            'naLocationRegisterEquipments.nonconformities.hazard',
            'naLocationNormalHazard'
        ]);
        // todo recheck hazard in both Equipment and Location, have na location also
        $na_equipments = $this->equipmentService->listNaEquipments($property_id, ['nonconformities.hazard']);
        $na_hazard = $this->hazardService->listNaHazards($property_id, $this->getAssessType($classification));//N/A location/area and not link to any equipment
        $fire_exists = $this->assessmentService->getFireExistRegister($property_id);
        $assembly_points = $this->assessmentService->getAssemblyPointRegister($property_id);
        $vehicle_parking = $this->assessmentService->getVehicleParkingRegister($property_id);

        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment to add on property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);

        return view('shineCompliance.assessment.add',
            compact('property', 'classification', 'assessmentTypes', 'leads', 'projects', 'property_id','systems','areas',
                'fire_exists', 'assembly_points', 'vehicle_parking','na_equipments','na_hazard','users','clients'));
    }

    public function postAdd($classification, CreateAssessmentRequest $request)
    {
        $result = $this->assessmentService->createAssessment($classification, $request->validated());

        if ($result['status_code'] == STATUS_OK) {
            return redirect()->route('shineCompliance.assessment.show', ['assess_id' => $result['data']->id])
                ->with('msg', $result['msg']);
        }

        return redirect()->back()->with('err', $result['msg']);
    }

    public function getEdit($assess_id)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $assessmentTypes = $this->assessmentService->getAssessmentTypes($assessment->assess_classification);
        $leads = $this->assessmentService->getLeadUsers();
        $users = $this->assessmentService->getAssessors();
        $projects = $this->projectService->getSurveyProjects($assessment->property_id);
        $clients = $this->clientService->getAllClients();
        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment to edit on property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);

        return view('shineCompliance.assessment.edit', compact('assessment', 'assessmentTypes', 'leads', 'projects','users', 'clients'));
    }

    public function postEdit($assess_id, EditAssessmentRequest $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }
        $result = $this->assessmentService->updateAssessment($assess_id, $request->validated());

        if ($result['status_code'] == STATUS_OK) {
            return redirect()->route('shineCompliance.assessment.show', ['assess_id' => $assess_id])
                ->with('msg', $result['msg']);
        }

        return redirect()->back()->with('err', $result['msg']);

    }

    public function postDecommission($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }
        $reason_decommissioned = $request->get('decommission_reason');
        $result = $this->assessmentService->decommissionAssessment($assessment, $reason_decommissioned);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function postRecommission($assess_id)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }
        $result = $this->assessmentService->recommissionAssessment($assessment);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function publishAssessment($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id,
            [   'property.propertyInfo','property.clients.mainUser',
                'property.vulnerableOccupant.vulnerableOccupantTypes',
                'project','unDecommissionHazardPdf',
                'unDecommissionHazardPdf.area', 'unDecommissionHazardPdf.location', 'unDecommissionHazardPdf.nonconformity.equipment.equipmentType',
                'unDecommissionHazardPdf.hazardType','unDecommissionHazardPdf.hazardVerb','unDecommissionHazardPdf.hazardNoun',
                'assessor.clients',
                'fireExits.location','fireExits.area','fireExits',
                'assemblyPoints.location','assemblyPoints.area','assemblyPoints',
                'vehicleParking.location','vehicleParking.area','vehicleParking',
                'plans',
                'samples'])){
            abort(404);
        }
        $publish_draft = $request->has('assessment_draft');
        $result = $this->generateAssessmentPDFService->publishAssessment($assessment, $publish_draft);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function sendAssessment($assess_id)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }
        $result = $this->assessmentService->sendAssessment($assessment);
        if (isset($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->back()->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function getEditObjectiveScope($assess_id)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $managementInfoQueries = $this->assessmentService->getManagementInfoQueries($assess_id);
        $otherInfoQueries = $this->assessmentService->getOtherInfoQueries($assess_id);

        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment to edit objective scope";
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);
        return view('shineCompliance.assessment.get_edit_objective_scope', compact('assessment',
                    'managementInfoQueries',
                    'otherInfoQueries'));
    }

    public function postEditObjectiveScope($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $executiveSummary = $request->get('executive_summary');
        $objectiveScope = $request->get('objective_scope');
        if ($assessment->classification == ASSESSMENT_FIRE_TYPE) {
            $management_answer = $request->get('management_answer');
            $management_answer_other = $request->get('management_answer_other');
            $other_answer = $request->get('other_answer');
            $result = $this->assessmentService->updateObjectiveScope($assess_id, $objectiveScope, $executiveSummary, $management_answer, $management_answer_other, $other_answer);
        } else {
            $result = $this->assessmentService->updateObjectiveScope($assess_id, $objectiveScope, $executiveSummary);
        }

        if ($result['status_code'] == STATUS_OK) {
            return redirect()->route('shineCompliance.assessment.show', ['assess_id' => $assess_id])
                ->with('msg', $result['msg']);
        }

        return redirect()->back()->with('err', $result['msg']);
    }

    public function getEditPropertyInformation($assess_id)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $propertyInfo = json_decode($assessment->assessmentInfo->property_information, true);

        $programmeTypes = $this->propertyService->getAllProgrammeType();
        $primaryUses = $this->propertyService->loadDropdownText(PRIMARY_AND_SECONDARY_USE_ID);
        $dataDropdowns = $this->propertyService->getAllPropertyDropdownTitle($assessment->property, json_decode($assessment->assessmentInfo->property_information ?? '', false));
        $propertyStatus = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_STATUS_ID);
        $propertyOccupied = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_OCCUPIED_ID);
        $listedBuilding = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_LISTED_BUILDING_ID);
        $parkingArrangements = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PARKING_ARRANGEMENTS_ID);
        $vulnerableTypes = $this->propertyService->getVulnerableTypes();
        $constructionMaterials = $this->propertyService->getAllConstructionMaterials();
        $evacuationStrategyDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_EVACUATION_STRATEGY_ID);
        $FRAOverallDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_FRA_OVERALL_RISK_ID);
        $floorDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_FLOOR_ID);
        $stairDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_STAIR_ID);
        $wallConstructionDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_WALL_CONSTRUCTION_ID);
        $wallFinishDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_WALL_FINISH_ID);
        $propertyTypeDropdown = $this->propertyService->getPropertyInfoDropdown(PROPERTY_INFO_PROPERTY_TYPE_ID);
        $asset_class = $this->propertyService->getAllAssetClass(true);
        $asset_type = $this->propertyService->getAllAssetClass(false);

        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment to edit property information";
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);
        return view('shineCompliance.assessment.get_edit_property_information', compact('assessment',
            'propertyInfo',
            'programmeTypes',
            'primaryUses',
            'dataDropdowns',
            'propertyStatus',
            'propertyOccupied',
            'listedBuilding',
            'parkingArrangements',
            'vulnerableTypes',
            'constructionMaterials',
            'floorDropdown',
            'stairDropdown',
            'wallConstructionDropdown',
            'wallFinishDropdown',
            'propertyTypeDropdown',
            'asset_class',
            'asset_type',
            'evacuationStrategyDropdown',
            'FRAOverallDropdown')
        );
    }

    public function postEditPropertyInformation($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)) {
            abort(404);
        }

        $result = $this->assessmentService->updatePropertyInformation($assess_id, $request);

        if (isset($result) and !is_null($result)) {
            if ($result['status_code'] == 200) {
                return redirect()->route('shineCompliance.assessment.show', ['assess_id' => $assess_id])
                    ->with('msg', $result['msg']);
            } else {
                return redirect()->back()->with('err', $result['msg']);
            }
        }
    }

    public function getEditQuestionnaire($assess_id){
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }
        $sections = $this->assessmentService->getQuestionnaire($assess_id, $assessment->classification);
        // log audit
        $comment = \Auth::user()->full_name . " viewed assessment to edit questionnaire";
        \ComplianceHelpers::logAudit(ASSESSMENT_TYPE, $assessment->id, AUDIT_ACTION_VIEW, $assessment->reference, $assessment->property_id, $comment);

        return view('shineCompliance.assessment.get_add_edit_questionnaire', compact('sections','assessment'));
    }

    public function postEditQuestionnaire($assess_id, Request $request){
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $updateQuestionnaire = $this->assessmentService->updateQuestionnaire($request->all(), $assessment->id, $assessment->classification);
        if (isset($updateQuestionnaire) and !is_null($updateQuestionnaire)) {
            if ($updateQuestionnaire['status_code'] == 200) {
                return redirect()->route('shineCompliance.assessment.show',['assess_id' => $assess_id])->with('msg', $updateQuestionnaire['msg']);
            } else {
                return redirect()->back()->with('err', $updateQuestionnaire['msg']);
            }
        }
    }

    public function updateFireSafety(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'assess_id' => 'required',
            'fire_safety' => 'nullable',
            'fire_safety-other' => 'nullable',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }

        $result = $this->assessmentService->updateFireSafety($validator->validated());

        \Session::flash('msg', $result['msg']);
        return response()->json(['status_code' => $result['status_code'], 'success'=> $result['msg']]);
    }

    public function decommissionEquipment($equip_id){
        if(!$equipment = $this->assessmentService->getEquipmentDetail($equip_id)){
            abort(404);
        }
        $decommissionEquipment = $this->assessmentService->decommissionEquipment($equipment);
        if (isset($decommissionEquipment)) {
            if ($decommissionEquipment['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionEquipment['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionEquipment['msg']);
            }
        }
    }

    public function recommissionEquipment($equip_id){
        if(!$hazard = $this->assessmentService->getEquipmentDetail($equip_id)){
            abort(404);
        }
        $recommissionEquiment = $this->assessmentService->recommissionEquipment($hazard);
        if (isset($recommissionEquiment)) {
            if ($recommissionEquiment['status_code'] == 200) {
                return redirect()->back()->with('msg', $recommissionEquiment['msg']);
            } else {
                return redirect()->back()->with('err', $recommissionEquiment['msg']);
            }
        }
    }

    public function getSpecificDropdown(Request $request) {

        $parent_id = ($request->has('parent_id')) ? $request->parent_id : 0;

        $dropdowns = $this->assessmentService->getHazardSpecificLocation($parent_id);
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

    public function getLocationsByAssessmentAndArea($assess_id, $area_id)
    {
        $locations = $this->assessmentService->getLocationsByAssessmentAndArea($assess_id, $area_id);

        return response()->json($locations);
    }

    public function updateOrCreatePropertyPlan(Request $request) {

        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'assess_id' => 'required',
            'plan_reference' => 'required|max:255',
            'plan_date' => 'nullable|date_format:d/m/Y',
            'document' => 'required_without:id|file|max:100000',
            'description' => 'nullable|max:255'
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        if ($request->has('id')) {
            $propertyPlan = $this->assessmentService->updateOrCreatePropertyPlan($request->all(), $request->id);
        } else {
            $propertyPlan = $this->assessmentService->updateOrCreatePropertyPlan($request->all());
        }

        if (isset($propertyPlan) and !is_null($propertyPlan)) {
            \Session::flash('msg', $propertyPlan['msg']);
            return response()->json(['status_code' => $propertyPlan['status_code'], 'success'=> $propertyPlan['msg']]);
        }
    }

    public function postApproval($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $result =$this->assessmentService->approvalAssessment($assessment);

        if ($result['status_code'] == 200) {
            return redirect()->back()->with('msg', $result['msg']);
        } else {
            return redirect()->back()->with('err', $result['msg']);
        }
    }

    public function postReject($assess_id, Request $request)
    {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $result =$this->assessmentService->rejectAssessment($assessment, $request->except(['_token']));
        if ($result['status_code'] == 200) {
            return redirect()->back()->with('msg', $result['msg']);
        } else {
            return redirect()->back()->with('err', $result['msg']);
        }
    }

    public function postCancel($assess_id) {
        if(!$assessment = $this->assessmentService->getAssessmentDetail($assess_id)){
            abort(404);
        }

        $cancelAssessment = $this->assessmentService->cancelAssessment($assessment);
        if (isset($cancelAssessment) and !is_null($cancelAssessment)) {
            if ($cancelAssessment['status_code'] == 200) {
                return redirect()->back()->with('msg', $cancelAssessment['msg']);
            } else {
                return redirect()->back()->with('err', $cancelAssessment['msg']);
            }
        }
    }

    public function downloadAssessPDF($id, Request $request) {
        return $this->generateAssessmentPDFService->downloadAssessPDF($id, $request->type ?? '');
    }

    private function getAssessType($type)
    {
        $assessType = 0;
        switch ($type) {
            case ASBESTOS:
                $assessType = ASSESSMENT_ASBESTOS_TYPE;
                break;
            case FIRE:
                $assessType = ASSESSMENT_FIRE_TYPE;
                break;
            case GAS:
                $assessType = ASSESSMENT_GAS_TYPE;
                break;
            case WATER:
                $assessType = ASSESSMENT_WATER_TYPE;
                break;
        }

        return $assessType;
    }

    public function updateOrCreateSampling(Request $request) {
        $validator = \Validator::make($request->all(), [
            'id' => 'sometimes',
            'property_id' => 'required',
            'sample_reference' => 'required',
            'assess_id' => 'required',
            'date' => 'nullable|date_format:d/m/Y',
            'document' => 'required_without:id|file|max:100000',
            'description' => 'nullable|max:255',
        ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }

        if ($request->has('id')) {
            $propertyPlan = $this->assessmentService->updateOrCreateSampling($request->all(), $request->id);
        } else {

            $propertyPlan = $this->assessmentService->updateOrCreateSampling($request->all());
        }

        if (isset($propertyPlan) and !is_null($propertyPlan)) {
            \Session::flash('msg', $propertyPlan['msg']);
            return response()->json(['status_code' => $propertyPlan['status_code'], 'success'=> $propertyPlan['msg']]);
        }
    }

    public function getOtherHazardIdentifiedQuestions(Request $request) {
        $validator = \Validator::make($request->all(), [
            'assessment_id' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $assessment_id = $request->assessment_id ?? '';
        $question = AssessmentQuestion::where('id', OTHER_HAZARD_IDENTIFIED_QUESTION_ID)->first();
        $answers = AssessmentValue::where(['assess_id' => $assessment_id, 'question_id' => OTHER_HAZARD_IDENTIFIED_QUESTION_ID])->get();
        $html = view('shineCompliance.forms.assessment_other_hazard_identified_questions', [
            'question' => $question,
            'answers'=> $answers,
            'assessment_id' => $assessment_id
        ])->render();
        $result  = response()->json(['status_code' =>200, 'data'=> $html]);
        return $result;
    }
}
