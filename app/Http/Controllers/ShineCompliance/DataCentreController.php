<?php


namespace App\Http\Controllers\ShineCompliance;


use App\Helpers\ComplianceHelpers;
use App\Http\Controllers\Controller;
use App\Models\RejectionType;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\ChartService;
use App\Services\ShineCompliance\ProjectService;
use App\Services\ShineCompliance\SurveyService;
use App\Services\ShineCompliance\DocumentService;
use App\Services\ShineCompliance\IncidentReportService;
use App\Repositories\WorkRequestRepository;
use Illuminate\Http\Request;

class DataCentreController extends Controller
{
    private $assessmentService;
    private $projectService;
    private $surveyService;
    private $documentService;
    private $chartService;
    private $incidentReportService;

    public function __construct(AssessmentService $assessmentService, SurveyService $surveyService,
                                ProjectService $projectService, DocumentService $documentService,
                                ChartService $chartService,
                                WorkRequestRepository $workRequestRepository,
                                IncidentReportService $incidentReportService)
    {
        $this->assessmentService = $assessmentService;
        $this->projectService = $projectService;
        $this->surveyService = $surveyService;
        $this->documentService = $documentService;
        $this->chartService = $chartService;
        $this->workRequestRepository = $workRequestRepository;
        $this->incidentReportService = $incidentReportService;
    }

    public function dataCentre()
    {
        $data_duty_manage_chart = $this->chartService->createComplianceChart(NULL);
        $data_accessibility_chart = $this->chartService->createAccessibilityChart(NULL);
        $data_risk_chart = $this->chartService->createRiskChart(NULL);
        $data_action_recommendation_chart = $this->chartService->createActionRecommendationChart(NULL);
        $data_reinspection = $this->chartService->createReinspectionChart(NULL);
        $data_pre_planned = $this->chartService->createPrePlannedChart(NULL);
        //data for showing chart
        return view('shineCompliance.dataCentre.dashboard',
            compact('data_accessibility_chart','data_risk_chart','data_action_recommendation_chart','data_reinspection','data_pre_planned','data_duty_manage_chart'));
    }

    public function getApproval()
    {
        $rejection_types = RejectionType::all();
        $works = $this->workRequestRepository->getListWorkWaitingForApproval();
        $asbestos_lead_admin = \CommonHelpers::getGetAdminAsbestosLead();
        $approvalAssessments = $this->assessmentService->getApprovalAssessment();
        $approvalSurveys = $this->surveyService->getApprovalSurvey();
        $approvalProjectDocs = $this->documentService->getListDocumentsOverDue('approval', APPROVAL_DOCUMENT_CATEGORY_ID, \Auth::user()->id, 4);
        $approvalIncident = $this->incidentReportService->getApprovalIncident();
        return view('shineCompliance.dataCentre.approval', compact('approvalAssessments', 'approvalSurveys',
            'approvalProjectDocs', 'asbestos_lead_admin','approvalIncident','works','rejection_types'));
    }

    public function getRejected()
    {
        $assessments = $this->assessmentService->getRejectedAssessments();
        $surveys = $this->surveyService->getRejectedSurvey();
        $projectDocs = $this->documentService->getListDocumentsOverDue('rejected', APPROVAL_DOCUMENT_CATEGORY_ID, \Auth::user()->id, 3);
        $rejectIncident = $this->incidentReportService->getRejectIncident();
        $works = $this->workRequestRepository->getListWorkRejected();

        return view('shineCompliance.dataCentre.rejected', compact('assessments','surveys', 'projectDocs','rejectIncident','works'));
    }

    public function projects() {
        //fire
        $fire_assessment_projects = $this->projectService->getProjectByType(FIRE_ASSESSMENT, \Auth::user()->client_id );
        $fire_remedial_projects = $this->projectService->getProjectByType(FIRE_REMEDIAL_ASSESSMENT, \Auth::user()->client_id );
        $fire_independent_projects = $this->projectService->getProjectByType(FIRE_INDEPENDENT_ASSESSMENT, \Auth::user()->client_id );
        $fire_equipment_projects = $this->projectService->getProjectByType(FIRE_EQUIPMENT_ASSESSMENT, \Auth::user()->client_id );

        // water
        $water_assessment_projects = $this->projectService->getProjectByType(WATER_TESTING_ASSESSMENT, \Auth::user()->client_id );
        $water_remedial_projects = $this->projectService->getProjectByType(WATER_REMEDIAL_ASSESSMENT, \Auth::user()->client_id );
        $water_temp_projects = $this->projectService->getProjectByType(WATER_TEMP_ASSESSMENT, \Auth::user()->client_id );
        $water_legionella_risk = $this->projectService->getProjectByType(LEGIONELLA_ASSESSMENT, \Auth::user()->client_id );
        //asbestos
        $survey_only_projects = $this->projectService->getProjectByType(PROJECT_SURVEY_ONLY, \Auth::user()->client_id );
        $remediation_projects = $this->projectService->getProjectByType(PROJECT_REMEDIATION_REMOVAL, \Auth::user()->client_id );
        $demolition_projects = $this->projectService->getProjectByType(PROJECT_DEMOLITION, \Auth::user()->client_id );
        $analytical_projects = $this->projectService->getProjectByType(PROJECT_ANALYTICAL, \Auth::user()->client_id );

        $hs_projects = $this->projectService->getProjectByType(HS_ASSESSMENT_PROJECT, \Auth::user()->client_id );

        $asbestos = \CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS);
        $fire = \CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_FIRE, JOB_ROLE_FIRE);
        $water = \CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_WATER, JOB_ROLE_WATER);
        $hs =  \CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_HS, JOB_ROLE_H_S);
        return view('shineCompliance.dataCentre.project',[
            'water_temp_projects' => $water_temp_projects,
            'water_remedial_projects' => $water_remedial_projects,
            'water_assessment_projects' => $water_assessment_projects,
            'water_legionella_risk' => $water_legionella_risk,
            'fire_assessment_projects' => $fire_assessment_projects,
            'fire_independent_projects' => $fire_independent_projects,
            'fire_remedial_projects' => $fire_remedial_projects,
            'fire_equipment_projects' => $fire_equipment_projects,
            'survey_only_projects' => $survey_only_projects,
            'remediation_projects' => $remediation_projects,
            'demolition_projects' => $demolition_projects,
            'analytical_projects' => $analytical_projects,
            'hs_projects' => $hs_projects,
            'asbestos' => $asbestos,
            'fire' => $fire,
            'water' => $water,
            'hs' => $hs,
        ]);
    }

    public function surveys() {
        $client_id = \Auth::user()->client_id;
        $management_surveys = $this->surveyService->getSurveyByType($client_id, MANAGEMENT_SURVEY);
        $refurbish_surveys = $this->surveyService->getSurveyByType($client_id, REFURBISHMENT_SURVEY);
        $reinspection_surveys = $this->surveyService->getSurveyByType($client_id, RE_INSPECTION_REPORT);
        $demolition_surveys = $this->surveyService->getSurveyByType($client_id, DEMOLITION_SURVEY);
        $management_surveys_partial = $this->surveyService->getSurveyByType($client_id, MANAGEMENT_SURVEY_PARTIAL);
        $sample_survey = $this->surveyService->getSurveyByType($client_id, SAMPLE_SURVEY);


        return view('shineCompliance.dataCentre.survey',[

            'management_surveys' => $management_surveys,
            'reinspection_surveys' => $reinspection_surveys,
            'refurbish_surveys' => $refurbish_surveys,
            'demolition_surveys' => $demolition_surveys,
            'management_surveys_partial' => $management_surveys_partial,
            'sample_survey' => $sample_survey,
        ]);
    }

    public function critical() {

        $risk_color = "red_gradient";
//        $missing_surveys = $this->surveyService->missingSurvey();
        $missing_surveys_count = $this->surveyService->countMissingSurvey();
//        $missing_assessments = $this->surveyService->missingAssessment();
        $missing_assessments_count = $this->surveyService->countMissingAssessment();
        $overdue_surveys = $this->surveyService->listOverdueSurveys('critical', 2000);
        $overdue_assessments = $this->assessmentService->listOverdueAssessments('critical', 2000);
//        $overdue_audits = $this->surveyService->listOverdueAudits('critical', 2000);
        $overdue_audits = [];
        $reinspection_sites = $this->surveyService->getOverdueSurveySites2('critical');
        $reinspection_assessments = $this->assessmentService->getAsessmentReInspection('critical');

        $project_docs = $this->documentService->getListDocumentsOverDue('critical', APPROVAL_DOCUMENT_CATEGORY_ID);


        return view('shineCompliance.dataCentre.critical',[
            'overdue_surveys' => $overdue_surveys,
            'missing_assessments_count' => $missing_assessments_count,
            'reinspection_assessments' => $reinspection_assessments,
            'risk_color' => $risk_color,
            'overdue_audits' => $overdue_audits,
            'reinspection_sites' => $reinspection_sites,
            'project_docs' => $project_docs,
            'missing_surveys_count' => $missing_surveys_count,
            'overdue_assessments' => $overdue_assessments
        ]);
    }

    public function postAssessmentMissing(Request $request){
        $return = $this->surveyService->missingAssessment($request, $request->client_id ?? 0, $request->length ?? 0, $request->start ?? 0);
        return ComplianceHelpers::responseDataTable($request->draw ?? 0, $return[1], $return[1], $return[0]);
    }

    public function postSurveyMissing(Request $request){
        $return = $this->surveyService->missingSurvey($request, $request->client_id ?? 0, $request->length ?? 0, $request->start ?? 0);
        return ComplianceHelpers::responseDataTable($request->draw ?? 0, $return[1], $return[1], $return[0]);
    }

    public function urgent() {

        $risk_color = "orange_gradient";

        $overdue_surveys = $this->surveyService->listOverdueSurveys('urgent', 0);
        $urgent_assessments = $this->assessmentService->listOverdueAssessments('urgent', 2000);
//        $urgent_audits = $this->surveyService->listOverdueAudits('urgent', 2000);
        $urgent_audits = [];
        $reinspection_sites = $this->surveyService->getOverdueSurveySites2('urgent');
        $reinspection_assessments = $this->assessmentService->getAsessmentReInspection('urgent');

        $project_docs = $this->documentService->getListDocumentsOverDue('urgent', APPROVAL_DOCUMENT_CATEGORY_ID);

        return view('shineCompliance.dataCentre.urgent',[
            'overdue_surveys' => $overdue_surveys,
            'urgent_assessments' => $urgent_assessments,
            'reinspection_assessments' => $reinspection_assessments,
            'urgent_audits' => $urgent_audits,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'project_docs' => $project_docs
        ]);
    }

    public function important() {

        $risk_color = "yellow_gradient";

        $overdue_surveys = $this->surveyService->listOverdueSurveys('important', 0);
        $important_assessments = $this->assessmentService->listOverdueAssessments('important', 2000);
//        $important_audits = $this->surveyService->listOverdueAudits('important', 2000);
        $important_audits = [];
        $reinspection_sites = $this->surveyService->getOverdueSurveySites2('important');
        $reinspection_assessments = $this->assessmentService->getAsessmentReInspection('important');
        $project_docs = $this->documentService->getListDocumentsOverDue('important', APPROVAL_DOCUMENT_CATEGORY_ID);
        return view('shineCompliance.dataCentre.important',[
            'overdue_surveys' => $overdue_surveys,
            'reinspection_assessments' => $reinspection_assessments,
            'important_audits' => $important_audits,
            'important_assessments' => $important_assessments,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'project_docs' => $project_docs
        ]);

    }

    public function attention() {

        $risk_color = "light_blue_gradient";

        $overdue_surveys = $this->surveyService->listOverdueSurveys('attention', 0);
        $attention_assessments = $this->assessmentService->listOverdueAssessments('attention', 2000);
//        $attention_audits = $this->surveyService->listOverdueAudits('attention', 2000);
        $attention_audits = [];
        $reinspection_sites = $this->surveyService->getOverdueSurveySites2('attention');
        $reinspection_assessments = $this->assessmentService->getAsessmentReInspection('attention');
        $project_docs = $this->documentService->getListDocumentsOverDue('attention', APPROVAL_DOCUMENT_CATEGORY_ID);

        return view('shineCompliance.dataCentre.attention',[
            'attention_assessments' => $attention_assessments,
            'attention_audits' => $attention_audits,
            'reinspection_assessments' => $reinspection_assessments,
            'overdue_surveys' => $overdue_surveys,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'project_docs' => $project_docs
        ]);

    }

    public function deadline() {
        $risk_color = "grey";
        $overdue_surveys = $this->surveyService->listOverdueSurveys('deadline', 0);
        $deadline_assessments = $this->assessmentService->listOverdueAssessments('deadline', 2000);
//        $deadline_audits = $this->surveyService->listOverdueAudits('deadline', 2000);
        $deadline_audits = [];
        $reinspection_assessments = $this->assessmentService->getAsessmentReInspection('deadline');
        $reinspection_sites = $this->surveyService->getOverdueSurveySites2('deadline');
        $project_docs = $this->documentService->getListDocumentsOverDue('deadline', APPROVAL_DOCUMENT_CATEGORY_ID);

        return view('shineCompliance.dataCentre.deadline',[
            'overdue_surveys' => $overdue_surveys,
            'deadline_assessments' => $deadline_assessments,
            'deadline_audits' => $deadline_audits,
            'reinspection_assessments' => $reinspection_assessments,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'project_docs' => $project_docs,
        ]);
    }

    public function assessment() {

        $risk_color = "grey";

        $waters = $this->assessmentService->listWaterRiskDataCentre();
        $fires = $this->assessmentService->listFireRiskDataCentre();
        $hs = $this->assessmentService->listHSDataCentre();

        return view('shineCompliance.dataCentre.assessment',[
            'fires' => $fires,
            'waters' => $waters,
            'hs' => $hs,
        ]);
    }

    public function certificate() {
        $risk_color = "grey";
        return view('shineCompliance.dataCentre.certificate',[
        ]);
    }

    public function audit() {
        $risk_color = "grey";
        return view('shineCompliance.dataCentre.audit',[
        ]);
    }
}
