<?php

namespace App\Http\Controllers;

use App\Models\RejectionType;
use App\Repositories\WorkRequestRepository;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\DocumentRepository;

class DataCentreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $route;

    public function __construct(ClientRepository $clientRepository, SurveyRepository $surveyRepository, ProjectRepository $projectRepository, DocumentRepository $documentRepository, WorkRequestRepository $workRequestRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->surveyRepository = $surveyRepository;
        $this->projectRepository = $projectRepository;
        $this->documentRepository = $documentRepository;
        $this->workRequestRepository = $workRequestRepository;
        $this->route = (object)[];
        $this->route->name  = \Route::currentRouteName();

    }

    /**
     * Show my organisation by id.
     *
     */
    public function index()
    {

    }

    public function myNotifications() {
        $this->route->title = 'My Notifications';

        $checked_projects = $this->projectRepository->getCheckedProjects();
        return view('data_centre.my_notifications',['route' => $this->route, 'checked_projects' => $checked_projects]);
    }

    public function surveys() {
        if(!\CompliancePrivilege::checkPermission(SURVEYS_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }

        $this->route->title = 'Surveys';
        $client_id = \Auth::user()->client_id;
        if (\Auth::user()->clients->client_type == 1) {
            $management_surveys = $this->surveyRepository->getSurveyByType($client_id, MANAGEMENT_SURVEY);
            $refurbish_surveys = $this->surveyRepository->getSurveyByType($client_id, REFURBISHMENT_SURVEY);
            $reinspection_surveys = $this->surveyRepository->getSurveyByType($client_id, RE_INSPECTION_REPORT);
            $demolition_surveys = $this->surveyRepository->getSurveyByType($client_id, DEMOLITION_SURVEY);
            $management_surveys_partial = $this->surveyRepository->getSurveyByType($client_id, MANAGEMENT_SURVEY_PARTIAL);
            $sample_surveys = $this->surveyRepository->getSurveyByType($client_id, SAMPLE_SURVEY);
        } else {
            $management_surveys = $this->surveyRepository->getSurveyByType(false, MANAGEMENT_SURVEY);
            $refurbish_surveys = $this->surveyRepository->getSurveyByType(false, REFURBISHMENT_SURVEY);
            $reinspection_surveys = $this->surveyRepository->getSurveyByType(false, RE_INSPECTION_REPORT);
            $demolition_surveys = $this->surveyRepository->getSurveyByType(false, DEMOLITION_SURVEY);
            $management_surveys_partial = $this->surveyRepository->getSurveyByType(false, MANAGEMENT_SURVEY_PARTIAL);
            $sample_surveys = $this->surveyRepository->getSurveyByType(false, SAMPLE_SURVEY);
        }

        return view('data_centre.surveys',[
            'route' => $this->route,
            'management_surveys' => $management_surveys,
            'reinspection_surveys' => $reinspection_surveys,
            'refurbish_surveys' => $refurbish_surveys,
            'demolition_surveys' => $demolition_surveys,
            'management_surveys_partial' => $management_surveys_partial,
            'sample_surveys' => $sample_surveys,
        ]);
    }

    public function projects() {
        if(!\CompliancePrivilege::checkPermission(PROJECTS_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Project';
        if (\Auth::user()->clients->client_type == 1) {
            $survey_only_projects = $this->projectRepository->getProjectByType(PROJECT_SURVEY_ONLY, \Auth::user()->client_id );
            $remediation_projects = $this->projectRepository->getProjectByType(PROJECT_REMEDIATION_REMOVAL, \Auth::user()->client_id );
            $demolition_projects = $this->projectRepository->getProjectByType(PROJECT_DEMOLITION, \Auth::user()->client_id );
            $analytical_projects = $this->projectRepository->getProjectByType(PROJECT_ANALYTICAL, \Auth::user()->client_id );

        } else {
            if (in_array(PROJECT_SURVEY_ONLY, \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))) {
                $survey_only_projects = $this->projectRepository->getProjectByType(PROJECT_SURVEY_ONLY);
            } else {
                $survey_only_projects = false;
            }

            if (in_array(PROJECT_REMEDIATION_REMOVAL, \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))) {
                $remediation_projects = $this->projectRepository->getProjectByType(PROJECT_REMEDIATION_REMOVAL );
            } else {
                $remediation_projects = false;
            }

            if (in_array(PROJECT_DEMOLITION, \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))) {
                $demolition_projects = $this->projectRepository->getProjectByType(PROJECT_DEMOLITION );
            } else {
                $demolition_projects = false;
            }

            if (in_array(PROJECT_ANALYTICAL, \CompliancePrivilege::getPermission(PROJECT_TYPE_PERMISSION))) {
                $analytical_projects = $this->projectRepository->getProjectByType(PROJECT_ANALYTICAL );
            } else {
                $analytical_projects = false;
            }
        }
        return view('data_centre.projects',[
            'route' => $this->route,
            'survey_only_projects' => $survey_only_projects,
            'remediation_projects' => $remediation_projects,
            'demolition_projects' => $demolition_projects,
            'analytical_projects' => $analytical_projects,

        ]);
    }

    public function critical() {
        if(!\CompliancePrivilege::checkPermission(CRITICAL_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Critical';
        $risk_color = "red_gradient";
        $missing_surveys = $this->surveyRepository->missingSurvey();
        $overdue_surveys = $this->surveyRepository->listOverdueSurveys('critical', 0);
        $reinspection_sites = $this->surveyRepository->getOverdueSurveySites2('critical',\Auth::user()->id);

        $planningDocs = $this->documentRepository->getListDocumentsOverDue('critical', PLANNING_DOC_CATEGORY);
        $preStartDocs = $this->documentRepository->getListDocumentsOverDue('critical', PRE_START_DOC_CATEGORY);
        $siteRecordDocs = $this->documentRepository->getListDocumentsOverDue('critical', SITE_RECORDS_DOC_CATEGORY);
        $completionDocs = $this->documentRepository->getListDocumentsOverDue('critical', COMPLETION_DOC_CATEGORY);

        return view('data_centre.critical',[
            'route' => $this->route,
            'overdue_surveys' => $overdue_surveys,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'planningDocs' => $planningDocs,
            'preStartDocs' => $preStartDocs,
            'siteRecordDocs' => $siteRecordDocs,
            'completionDocs' => $completionDocs,
            'missing_surveys' => $missing_surveys,
        ]);
    }

    public function urgent() {
        if(!\CompliancePrivilege::checkPermission(URGENT_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Urgent';
        $risk_color = "orange_gradient";

        $overdue_surveys = $this->surveyRepository->listOverdueSurveys('urgent', 0);
        $reinspection_sites = $this->surveyRepository->getOverdueSurveySites2('urgent',\Auth::user()->id);

        $planningDocs = $this->documentRepository->getListDocumentsOverDue('urgent', PLANNING_DOC_CATEGORY);
        $preStartDocs = $this->documentRepository->getListDocumentsOverDue('urgent', PRE_START_DOC_CATEGORY);
        $siteRecordDocs = $this->documentRepository->getListDocumentsOverDue('urgent', SITE_RECORDS_DOC_CATEGORY);
        $completionDocs = $this->documentRepository->getListDocumentsOverDue('urgent', COMPLETION_DOC_CATEGORY);

        return view('data_centre.urgent',[
            'route' => $this->route,
            'overdue_surveys' => $overdue_surveys,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'planningDocs' => $planningDocs,
            'preStartDocs' => $preStartDocs,
            'siteRecordDocs' => $siteRecordDocs,
            'completionDocs' => $completionDocs,
        ]);
    }

    public function important() {
        if(!\CompliancePrivilege::checkPermission(IMPORTANT_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Important';
        $risk_color = "yellow_gradient";

        $overdue_surveys = $this->surveyRepository->listOverdueSurveys('important', 0);
        $reinspection_sites = $this->surveyRepository->getOverdueSurveySites2('important',\Auth::user()->id);

        $planningDocs = $this->documentRepository->getListDocumentsOverDue('important', PLANNING_DOC_CATEGORY);
        $preStartDocs = $this->documentRepository->getListDocumentsOverDue('important', PRE_START_DOC_CATEGORY);
        $siteRecordDocs = $this->documentRepository->getListDocumentsOverDue('important', SITE_RECORDS_DOC_CATEGORY);
        $completionDocs = $this->documentRepository->getListDocumentsOverDue('important', COMPLETION_DOC_CATEGORY);
        return view('data_centre.important',[
            'route' => $this->route,
            'overdue_surveys' => $overdue_surveys,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'planningDocs' => $planningDocs,
            'preStartDocs' => $preStartDocs,
            'siteRecordDocs' => $siteRecordDocs,
            'completionDocs' => $completionDocs,
        ]);

    }

    public function attention() {
        if(!\CompliancePrivilege::checkPermission(ATTENTION_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Attention';
        $risk_color = "light_blue_gradient";

        $overdue_surveys = $this->surveyRepository->listOverdueSurveys('attention', 0);
        $reinspection_sites = $this->surveyRepository->getOverdueSurveySites2('attention',\Auth::user()->id);

        $planningDocs = $this->documentRepository->getListDocumentsOverDue('attention', PLANNING_DOC_CATEGORY);
        $preStartDocs = $this->documentRepository->getListDocumentsOverDue('attention', PRE_START_DOC_CATEGORY);
        $siteRecordDocs = $this->documentRepository->getListDocumentsOverDue('attention', SITE_RECORDS_DOC_CATEGORY);
        $completionDocs = $this->documentRepository->getListDocumentsOverDue('attention', COMPLETION_DOC_CATEGORY);

        return view('data_centre.attention',[
            'route' => $this->route,
            'overdue_surveys' => $overdue_surveys,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'planningDocs' => $planningDocs,
            'preStartDocs' => $preStartDocs,
            'siteRecordDocs' => $siteRecordDocs,
            'completionDocs' => $completionDocs,
        ]);

    }

    public function deadline() {
        if(!\CompliancePrivilege::checkPermission(DEADLINE_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Deadline';
        $risk_color = "grey";

        $overdue_surveys = $this->surveyRepository->listOverdueSurveys('deadline', 0);
        // $reinspection_sites = $this->surveyRepository->getOverdueSurveySites('deadline');
        $reinspection_sites = $this->surveyRepository->getOverdueSurveySites2('deadline',\Auth::user()->id);
        $planningDocs = $this->documentRepository->getListDocumentsOverDue('deadline', PLANNING_DOC_CATEGORY);
        $preStartDocs = $this->documentRepository->getListDocumentsOverDue('deadline', PRE_START_DOC_CATEGORY);
        $siteRecordDocs = $this->documentRepository->getListDocumentsOverDue('deadline', SITE_RECORDS_DOC_CATEGORY);
        $completionDocs = $this->documentRepository->getListDocumentsOverDue('deadline', COMPLETION_DOC_CATEGORY);

        return view('data_centre.deadline',[
            'route' => $this->route,
            'overdue_surveys' => $overdue_surveys,
            'risk_color' => $risk_color,
            'reinspection_sites' => $reinspection_sites,
            'planningDocs' => $planningDocs,
            'preStartDocs' => $preStartDocs,
            'siteRecordDocs' => $siteRecordDocs,
            'completionDocs' => $completionDocs,
        ]);

    }

    public function approval() {
        if(!\CompliancePrivilege::checkPermission(APPROVAL_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $asbestos_lead_admin = \CommonHelpers::getGetAdminAsbestosLead();
        $this->route->title = 'Approval';
        $surveys = $this->surveyRepository->getApprovalSurvey();
        $works = $this->workRequestRepository->getListWorkWaitingForApproval();
        $planning_docs = $this->documentRepository->getListDocumentsOverDue('approval', PLANNING_DOC_CATEGORY, \Auth::user()->id, 1);
        $pre_start_docs = $this->documentRepository->getListDocumentsOverDue('approval', PRE_START_DOC_CATEGORY, \Auth::user()->id, 1);
        $site_records_docs = $this->documentRepository->getListDocumentsOverDue('approval', SITE_RECORDS_DOC_CATEGORY, \Auth::user()->id, 1);
        $completion_docs = $this->documentRepository->getListDocumentsOverDue('approval', COMPLETION_DOC_CATEGORY, \Auth::user()->id, 1);
        if (\Auth::user()->clients->client_type == 1) {
            $docs = $this->documentRepository->getListDocumentsOverDue('approval', CONTRACTOR_DOC_CATEGORY, \Auth::user()->id, 1);
            $approval_survey = true;
            $approval_contractor_doc = true;
            $approval_planning = true;
            $approval_pre_start = true;
            $approval_site_records = true;
            $approval_completion = true;
        } else {
            //priviliege
            if (\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, 2)) {
                $docs = $this->documentRepository->getListDocumentsOverDue('approval', CONTRACTOR_DOC_CATEGORY, \Auth::user()->id, 1);
            } else {
                $docs = false;
            }

            $approval_survey =  \CompliancePrivilege::checkUpdatePermission(DATA_CENTRE_UPDATE_PRIV, DC_SURVEYS_UPDATE_PRIV);
            $approval_contractor_doc = \CompliancePrivilege::checkUpdatePermission(DATA_CENTRE_UPDATE_PRIV, DC_DOCUMENT_UPDATE_PRIV);
            $approval_planning =  \CompliancePrivilege::checkUpdatePermission(DATA_CENTRE_UPDATE_PRIV, DC_PLANNING_DOCUMENT_UPDATE_PRIV);
            $approval_pre_start =  \CompliancePrivilege::checkUpdatePermission(DATA_CENTRE_UPDATE_PRIV, DC_PRE_START_DOCUMENT_UPDATE_PRIV);
            $approval_site_records =  \CompliancePrivilege::checkUpdatePermission(DATA_CENTRE_UPDATE_PRIV, DC_SITE_RECORD_DOCUMENT_UPDATE_PRIV);
            $approval_completion =  \CompliancePrivilege::checkUpdatePermission(DATA_CENTRE_UPDATE_PRIV, DC_COMPLETION_DOCUMENT_UPDATE_PRIV);

        }

        $rejection_types = RejectionType::all();

        return view('data_centre.approval',[
            'route' => $this->route,
            'surveys' => $surveys,
            'works' => $works,
            'docs' => $docs,
            'approval_survey' => $approval_survey,
            'approval_contractor_doc' => $approval_contractor_doc,
            'approval_planning' => $approval_planning,
            'approval_pre_start' => $approval_pre_start,
            'approval_site_records' => $approval_site_records,
            'approval_completion' => $approval_completion,
            'planning_docs' => $planning_docs,
            'pre_start_docs' => $pre_start_docs,
            'site_records_docs' => $site_records_docs,
            'completion_docs' => $completion_docs,
            'asbestos_lead_admin' => $asbestos_lead_admin,
            'rejection_types' => $rejection_types
        ]);
    }

    public function rejected() {
        if(!\CompliancePrivilege::checkPermission(REJECTED_VIEW_PRIV) and \CommonHelpers::isSystemClient()){
            abort(404);
        }
        $this->route->title = 'Rejected';
        $surveys = $this->surveyRepository->getRejectedSurvey();
        $works = $this->workRequestRepository->getListWorkRejected();

        $planning_docs = $this->documentRepository->getListDocumentsOverDue('approval', PLANNING_DOC_CATEGORY, \Auth::user()->id, 3);
        $pre_start_docs = $this->documentRepository->getListDocumentsOverDue('approval', PRE_START_DOC_CATEGORY, \Auth::user()->id, 3);
        $site_records_docs = $this->documentRepository->getListDocumentsOverDue('approval', SITE_RECORDS_DOC_CATEGORY, \Auth::user()->id, 3);
        $completion_docs = $this->documentRepository->getListDocumentsOverDue('approval', COMPLETION_DOC_CATEGORY, \Auth::user()->id, 3);
        if (\Auth::user()->clients->client_type == 1) {
            $docs = $this->documentRepository->getListDocumentsOverDue('approval', CONTRACTOR_DOC_CATEGORY, \Auth::user()->id, 3);

        } else {
            //priviliege
            if (\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, 2)) {
                $docs = $this->documentRepository->getListDocumentsOverDue('approval', CONTRACTOR_DOC_CATEGORY, \Auth::user()->id, 3);
            } else {
                $docs = false;
            }
        }

        return view('data_centre.rejected',[
            'route' => $this->route,
            'surveys' => $surveys,
            'works' => $works,
            'docs' => $docs,
            'planning_docs' => $planning_docs,
            'pre_start_docs' => $pre_start_docs,
            'site_records_docs' => $site_records_docs,
            'completion_docs' => $completion_docs,
        ]);
    }

}
