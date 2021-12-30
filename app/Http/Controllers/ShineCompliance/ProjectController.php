<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\ProjectService;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\ClientService;
use App\Http\Request\ShineCompliance\Project\ProjectCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    private $projectService;
    private $propertyService;
    private $clientService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ProjectService $projectService,
        PropertyService $propertyService,
        ClientService $clientService
    )
    {
        $this->projectService = $projectService;
        $this->propertyService = $propertyService;
        $this->clientService = $clientService;
    }

    /**
     * Show my organisation by id.
     *
     */

    public function listProject($property_id){
        //check privilege
        // if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id) and \CommonHelpers::isSystemClient()) {
        //     abort(404);
        // }
        $property = $this->propertyService->getProperty($property_id, ['parents']);
        $projects_asbestos = $this->projectService->getPropertyProjectType($property_id,ASBESTOS_PROJECT);
        $decommissioned_projects_asbestos = $projects_asbestos->where('decommissioned', PROJECT_DECOMMISSION);
        $projects_fire = $this->projectService->getPropertyProjectType($property_id,FIRE_PROJECT);
        $decommissioned_projects_fire = $projects_fire->where('decommissioned', PROJECT_DECOMMISSION);
        $projects_water = $this->projectService->getPropertyProjectType($property_id,WATER_PROJECT);
        $decommissioned_projects_water = $projects_water->where('decommissioned', PROJECT_DECOMMISSION);

        $project_survey_only = $projects_asbestos->where('project_type',PROJECT_SURVEY_ONLY)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $project_remediation = $projects_asbestos->where('project_type',PROJECT_REMEDIATION_REMOVAL)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $project_demolition = $projects_asbestos->where('project_type',PROJECT_DEMOLITION)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $project_analytical = $projects_asbestos->where('project_type',PROJECT_ANALYTICAL)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $fire_equiment_ass = $projects_fire->where('project_type',FIRE_EQUIPMENT_ASSESSMENT)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $fire_independent_ass = $projects_fire->where('project_type',FIRE_INDEPENDENT_ASSESSMENT)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $fire_remedial_ass = $projects_fire->where('project_type',FIRE_REMEDIAL_ASSESSMENT)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $fire_ass= $projects_fire->where('project_type',FIRE_ASSESSMENT)->where('decommissioned', PROJECT_UNDECOMMISSION);
        $projects_data_hs = $this->projectService->getPropertyProjectType($property_id,HS_PROJECT);
        $projects_hs = $projects_data_hs->where('decommissioned', PROJECT_UNDECOMMISSION);
        $decommissioned_projects_hs = $projects_data_hs->where('decommissioned', PROJECT_DECOMMISSION);

        $legionella_risk_assessment_projects = $projects_water->where('project_type', LEGIONELLA_ASSESSMENT);
        $water_remedial_assessment_projects = $projects_water->where('project_type', WATER_REMEDIAL_ASSESSMENT);
        $water_temperature_assessment_projects = $projects_water->where('project_type', WATER_TEMP_ASSESSMENT);
        $water_testing_assessment_projects = $projects_water->where('project_type', WATER_TESTING_ASSESSMENT);

        $asbestos = true;
        $fire = true;
        $water = true;
        $health_and_safety = true;
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS)){
            $asbestos = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_FIRE, JOB_ROLE_FIRE)){
            $fire = false;
        }
        if((\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_WATER, JOB_ROLE_WATER)) || !env('WATER_MODULE') ) {
            $water = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_HS, JOB_ROLE_H_S)){
            $health_and_safety = false;
        }
        $can_add_asbestos = true;
        $can_add_fire = true;
        $can_add_water = true;
        $can_add_hs = true;
        if (!\CommonHelpers::isSystemClient()) {
            $can_add_asbestos = false;
            $can_add_fire = false;
            $can_add_water = false;
            $can_add_hs = false;
        } elseif (\CommonHelpers::isSystemClient()) {
            $edit_property_permission = \CompliancePrivilege::checkUpdatePermission(PROPERTY_PERMISSION, $property_id);
            // check update permission for asbestos
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_ASBESTOS,JOB_ROLE_ASBESTOS ) || !$edit_property_permission) {
                $can_add_asbestos = false;
            }
            // check update permission for fire
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_FIRE,JOB_ROLE_FIRE ) || !$edit_property_permission) {
                $can_add_fire = false;
            }
            // check update permission for water
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_WATER,JOB_ROLE_WATER ) || !$edit_property_permission) {
                $can_add_water = false;
            }
            // check update permission for water
            if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_HS,JOB_ROLE_H_S ) || !$edit_property_permission) {
                $can_add_hs = false;
            }
        }

        //log audit
        $comment = \Auth::user()->full_name . " viewed project list for property " . ($property->name ?? '');
        \ComplianceHelpers::logAudit(PROJECT_TYPE, $property->id, AUDIT_ACTION_VIEW, $property->reference, $property->property_id, $comment);
        return view('shineCompliance.project.project_list',
            compact('projects_asbestos','asbestos','fire', 'water','property','projects_fire', 'projects_water','can_add_hs',
             'can_add_asbestos','can_add_fire', 'can_add_water', 'health_and_safety','projects_hs','project_survey_only','project_remediation','project_demolition',
             'legionella_risk_assessment_projects','water_remedial_assessment_projects','water_temperature_assessment_projects', 'water_testing_assessment_projects',
             'project_analytical','fire_equiment_ass','fire_independent_ass','fire_remedial_ass','fire_ass',
             'decommissioned_projects_asbestos','decommissioned_projects_fire','decommissioned_projects_water','decommissioned_projects_hs'
        ));
    }

    public function index($project_id)
    {
        $project = $this->projectService->getProject($project_id);

        // work flow
        $work_flow = $project->workRequest->work_flow ?? 0;
        //check privilege wates permission
        $wates_user = false;
        if (\Auth::user()->client_id == 6 and $work_flow == WORK_FLOW_WATES and $project->status == PROJECT_TENDERING_IN_PROGRESS_STATUS) {
            $wates_user = true;
        }

        $wates_view = false;
        if (\Auth::user()->client_id == 6 and $work_flow == WORK_FLOW_WATES and $project->status == PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS) {
            $wates_view = true;
        }

        $wates_table_permission = false;
        if ($work_flow == WORK_FLOW_WATES and $project->status == PROJECT_TENDERING_IN_PROGRESS_STATUS) {
            $wates_table_permission = true;
        }

        if (is_null($project)) {
            abort(404);
        }

        $in_contractors = true;
        $in_checked_contractors = true;
        if(\Auth::user()->clients->client_type != 0) {
            $in_contractors = in_array(\Auth::user()->client_id, explode(",",$project->contractors));
            $in_checked_contractors = in_array(\Auth::user()->client_id, explode(",",$project->checked_contractors));
        }
        // view privilege
        $tender_docs = $planning_docs = $pre_start_docs = $site_record_docs = $completion_docs = [];
        if(\Auth::user()->clients->client_type != 0) {
            $logged_user = \Auth::user();
            if ($project->status > 2 and !in_array($logged_user->client_id, explode(',', $project->checked_contractors))
                || $project->status < 3 and !in_array($logged_user->client_id, explode(',', $project->contractors))
            ) {
                abort(404);
            }
            $canBeUpdateThisProject = true;
            $updateTender = $updatePlanning = $updatePreStart = $updateSiteRecord = $updateCompletion = true;
            $updateContractor = true;

            $tender_docs = $this->projectService->getTenderBoxDocsContractor($project_id, TENDER_DOC_CATEGORY, $logged_user->client_id);
            $planning_docs = $this->projectService->getTenderBoxDocsContractor($project_id, PLANNING_DOC_CATEGORY, $logged_user->client_id);
            $pre_start_docs = $this->projectService->getTenderBoxDocsContractor($project_id, PRE_START_DOC_CATEGORY, $logged_user->client_id);
            $site_record_docs = $this->projectService->getTenderBoxDocsContractor($project_id, SITE_RECORDS_DOC_CATEGORY, $logged_user->client_id);
            $completion_docs = $this->projectService->getTenderBoxDocsContractor($project_id, COMPLETION_DOC_CATEGORY, $logged_user->client_id);
        } else {
            if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $project->property_id) ||
                !\CompliancePrivilege::checkPermission(PROJECT_TYPE_PERMISSION, $project->project_type) ) {
                abort(404);
            }

            $canBeUpdateThisProject = \CompliancePrivilege::checkUpdatePermission(PROJECTS_TYPE_UPDATE_PRIV, $project->project_type);
            $updateTender = \CompliancePrivilege::checkUpdatePermission(PROJECT_INFORMATIONS_UPDATE_PRIV, 1);
            $updatePlanning = \CompliancePrivilege::checkUpdatePermission(PROJECT_INFORMATIONS_UPDATE_PRIV, PLANNING_DOC_PRIVILEGE);
            $updatePreStart = \CompliancePrivilege::checkUpdatePermission(PROJECT_INFORMATIONS_UPDATE_PRIV, PRE_START_DOC_PRIVILEGE);
            $updateSiteRecord = \CompliancePrivilege::checkUpdatePermission(PROJECT_INFORMATIONS_UPDATE_PRIV, SITE_RECORDS_DOC_PRIVILEGE);
            $updateCompletion = \CompliancePrivilege::checkUpdatePermission(PROJECT_INFORMATIONS_UPDATE_PRIV, COMPLETION_DOC_DOC_PRIVILEGE);
            $updateContractor = \CompliancePrivilege::checkUpdatePermission(PROJECT_INFORMATIONS_UPDATE_PRIV, 2);

            $tender_docs = $this->projectService->getTenderBoxDocs($project_id, TENDER_DOC_CATEGORY);
            $planning_docs = $this->projectService->getTenderBoxDocs($project_id, PLANNING_DOC_CATEGORY);
            $pre_start_docs = $this->projectService->getTenderBoxDocs($project_id, PRE_START_DOC_CATEGORY);
            $site_record_docs = $this->projectService->getTenderBoxDocs($project_id, SITE_RECORDS_DOC_CATEGORY);
            $completion_docs = $this->projectService->getTenderBoxDocs($project_id, COMPLETION_DOC_CATEGORY);
        }

        $project_contractors = $this->projectService->getContractors($project->contractors);
        $project_surveys = $this->projectService->getProjectSurveys($project->survey_id);
        $linked_projects = $this->projectService->getLinkedProject($project->linked_project_id);
        $project_winner_contractors = $this->projectService->getContractors($project->checked_contractors);

        if (!is_null($project_contractors)) {
            foreach ($project_contractors as $contractor) {
                $contractor->data = $this->projectService->getContractorBoxDocs($project_id, CONTRACTOR_DOC_CATEGORY, $contractor->id);
            }
        }

        if (!is_null($project_surveys)) {
            foreach ($project_surveys as $survey) {
                $survey->data = $this->projectService->getSurveyDocs($survey);
            }
        }

        $gsk_docs = $this->projectService->getTenderBoxDocs($project_id, GSK_DOC_CATEGORY);

        $contractor_document_types = $this->projectService->listDocumentTypes(CONTRACTOR_DOC_TYPE);
        $gsk_document_types = $this->projectService->listDocumentTypes(GSK_DOC_TYPE);
        $tender_document_types = $this->projectService->listDocumentTypes(TENDER_DOC_TYPE);
        $planning_document_types = $this->projectService->listDocumentTypes(PLANNING_DOC_TYPE);
        $pre_start_document_types = $this->projectService->listDocumentTypes(PRE_START_DOC_TYPE);
        $site_record_document_types = $this->projectService->listDocumentTypes(SITE_RECORD_DOC_TYPE);
        $completion_document_types = $this->projectService->listDocumentTypes(COMPLETION_DOC_TYPE);
        $is_approval_project =  $this->projectService->is_approval_project($project_id);
        $work_streams = $this->projectService->getWorkStreams();

        $locked = ($project->status > 3) ? TRUE : FALSE;

        //log audit
        \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_VIEW, $project->reference,0 , null ,0 ,$project->property_id);
        return view('project.index',[
            'project' => $project,
            'wates_view' => $wates_view,
            'project_winner_contractors' => $project_winner_contractors,
            'wates_table_permission' => $wates_table_permission,
            'project_contractors' => $project_contractors,
            'tender_docs' => $tender_docs,
            'planning_docs' => $planning_docs,
            'pre_start_docs' => $pre_start_docs,
            'site_record_docs' => $site_record_docs,
            'completion_docs' => $completion_docs,
            'gsk_docs' => $gsk_docs,
            'project_surveys' => $project_surveys,
            'contractor_document_types' => $contractor_document_types,
            'gsk_document_types' => $gsk_document_types,
            'tender_document_types' => $tender_document_types,
            'planning_document_types' => $planning_document_types,
            'pre_start_document_types' => $pre_start_document_types,
            'site_record_document_types' => $site_record_document_types,
            'completion_document_types' => $completion_document_types,
            'is_approval_project' => $is_approval_project,
            'locked' => $locked,
            'in_contractors' => $in_contractors,
            'in_checked_contractors' => $in_checked_contractors,
            'canBeUpdateThisProject' => $canBeUpdateThisProject,
            'updateTender' => $updateTender,
            'updatePlanning' => $updatePlanning,
            'updatePreStart' => $updatePreStart,
            'updateSiteRecord' => $updateSiteRecord,
            'updateCompletion' => $updateCompletion,
            'updateContractor' => $updateContractor,
            'work_streams' => $work_streams,
            'wates_user' => $wates_user,
            'linked_projects' => $linked_projects
        ]);
    }

    public function getAddProject($property_id) {
        //check privilege
        if (!$property = $this->propertyService->getProperty($property_id, ['parents'])) {
             abort(404);
        }
        $project_types = $this->projectService->getProjectTypes();
        $asbestos_leads = $this->clientService->getClientUsers(1);
//        $sponsor_leads = $this->clientRepository->getClientUsers(1);
        $sponsors = $this->projectService->getSponsorList();
        $survey_types = $this->projectService->getSurveyTypes();
        $surveys = $this->propertyService->getPropertySurvey($property_id);
        $linked_projects = $this->propertyService->getLinkedPropertyProject($property_id);
        $rr_conditions = $this->projectService->getRRConditions();
        $survey_only_contractors = $this->projectService->getSelectContractors(PROJECT_SURVEY_ONLY);
        $remendiation_contractors = $this->projectService->getSelectContractors(PROJECT_REMEDIATION_REMOVAL);
        $demolition_contractors = $this->projectService->getSelectContractors(PROJECT_DEMOLITION);
        $analytical_contractors = $this->projectService->getSelectContractors(PROJECT_ANALYTICAL);
        $work_streams = $this->projectService->getWorkStreams();

        return view('shineCompliance.project.add_project',[
            'property' => $property,
            'project_types' => $project_types,
            'asbestos_leads' => $asbestos_leads,
//            'sponsor_leads' => $sponsor_leads,
            'sponsors' => $sponsors,
            'survey_types' => $survey_types,
            'surveys' => $surveys,
            'rr_conditions' => $rr_conditions,
            'survey_only_contractors' => $survey_only_contractors,
            'remendiation_contractors' => $remendiation_contractors,
            'demolition_contractors' => $demolition_contractors,
            'analytical_contractors' => $analytical_contractors,
            'work_streams' => $work_streams,
            'linked_projects' => $linked_projects
        ]);
    }

    public function postAddProject( ProjectCreateRequest $projectCreateRequest) {
        $validatedData = $projectCreateRequest->validated();
        $projectUpdate = $this->projectService->createProject($validatedData);

        if (isset($projectUpdate) and !is_null($projectUpdate)) {
            if ($projectUpdate['status_code'] == 200) {
                return redirect()->route('project.index', ['project_id' => $projectUpdate['data']])->with('msg', $projectUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $projectUpdate['msg']);
            }
        }
    }

    public function getEditProject($project_id) {
        $project = $this->projectService->getProject($project_id);
        //check privilege
        $work_flow = $project->workRequest->work_flow ?? 0;
        //check privilege wates permission
        $wates_user = false;
        if ((\Auth::user()->client_id == 6) and ($work_flow == WORK_FLOW_WATES) and ($project->status == PROJECT_TENDERING_IN_PROGRESS_STATUS)) {
            $wates_user = true;
        }

        // if (!$wates_user) {
        if (!\CommonHelpers::isSystemClient()) {
            if (!$wates_user) {
                abort(404);
            }
        } else {
            if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $project->property_id) || !\CompliancePrivilege::checkUpdatePermission(PROJECTS_TYPE_UPDATE_PRIV, $project->project_type)) {
                abort(404);
            }
        }
        // }

        $project_types = $this->projectService->getProjectTypes();
        $asbestos_leads = $this->clientService->getClientUsers(1);
        // $asbestos_lead_admin = \CommonHelpers::getGetAdminAsbestosLead();
        // $asbestos_leads = User::whereIn('id', $asbestos_lead_admin)->get();

        $sponsor_leads = $this->clientService->getClientUsers(1);
        $sponsors = $this->projectService->getSponsorList();
        $survey_types = $this->projectService->getSurveyTypes();
        $surveys = $this->propertyService->getPropertySurvey($project->property_id);
        $rr_conditions = $this->projectService->getRRConditions();
        $linked_projects = $this->propertyService->getLinkedPropertyProject($project->property_id, $project_id);

        $linked_survey = explode(",",$project->survey_id);
        $work_streams = $this->projectService->getWorkStreams();
        return view('shineCompliance.project.edit_project',[
            'project' => $project,
            'project_types' => $project_types,
            'asbestos_leads' => $asbestos_leads,
            'sponsor_leads' => $sponsor_leads,
            'sponsors' => $sponsors,
            'survey_types' => $survey_types,
            'surveys' => $surveys,
            'rr_conditions' => $rr_conditions,
            'linked_survey' => $linked_survey,
            'work_streams' => $work_streams,
            'linked_projects' => $linked_projects
        ]);
    }

    public function postEditProject($project_id, ProjectCreateRequest $projectCreateRequest) {
        $validatedData = $projectCreateRequest->validated();
        $projectUpdate = $this->projectService->createProject($validatedData, $project_id);

        if (isset($projectUpdate) and !is_null($projectUpdate)) {
            if ($projectUpdate['status_code'] == 200) {
                return redirect()->route('project.index', ['project_id' => $projectUpdate['data'] ])->with('msg', $projectUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $projectUpdate['msg']);
            }
        }
    }

    public function contractorSelect(Request $request) {
        $validator = \Validator::make($request->all(), [
            'type_id' => 'required',
            'selected' => 'sometimes',
        ]);

        if ($request->has('selected') || $request->has('checked_contractors')) {
            $selected = $request->selected;
            $checked_contractors = $request->checked_contractors;

            if (!is_null($selected) || !is_null($checked_contractors)) {
                $selected = explode(",", $selected);
                $checked_contractors = explode(",", $checked_contractors);
            } else {
                $selected = [];
                $checked_contractors = [];
            }
        } else {
            $selected = [];
            $checked_contractors = [];
        }

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $contractor_lists = $this->projectService->getSelectContractors($request->type_id);
        $html = view('shineCompliance.forms.form_multiple_option', [
            'title' => 'Contractors',
            'dropdown_list'=> $contractor_lists,
            'id_select' => 'contractors',
            'name' => 'contractors',
            'value_get' => 'name',
            'selected'=> $selected,
            'checked_contractors'=> $checked_contractors,
            'max_option' => count($contractor_lists) - 1 ,
            'edit_project' => $request->has('edit_project') ? true : false
        ])->render();
        return response()->json(['status_code' =>200, 'data'=> $html, 'total' => count($contractor_lists)]);
    }

    public function archiveProject($project_id, Request $request) {
        $type = $request->has('type') ? $request->type : '';
        $archiveProject = $this->projectService->archiveProject($project_id, $type);
        if (isset($archiveProject)) {
            if ($archiveProject['status_code'] == 200) {
                return redirect()->back()->with('msg', $archiveProject['msg']);
            } else {
                return redirect()->back()->with('err', $archiveProject['msg']);
            }
        }
    }
}
