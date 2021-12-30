<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\PropertyRepository;
use App\Repositories\ShineCompliance\ComplianceCategoryDocumentRepository;
use App\Repositories\ShineCompliance\HazardRepository;
use App\Http\Request\Project\ProjectCreateRequest;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClientRepository $clientRepository, ProjectRepository $projectRepository,
                                PropertyRepository $propertyRepository,
                                ComplianceCategoryDocumentRepository $complianceCategoryDocumentRepository,
                                HazardRepository $hazardRepository
    )
    {
        $this->clientRepository = $clientRepository;
        $this->projectRepository = $projectRepository;
        $this->propertyRepository = $propertyRepository;
        $this->complianceCategoryDocumentRepository = $complianceCategoryDocumentRepository;
        $this->hazardRepository = $hazardRepository;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index($project_id)
    {
        $project = $this->projectRepository->findWhere(['id' => $project_id])->first();
        //check privilege


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
        $tender_docs = $planning_docs = $pre_start_docs = $site_record_docs =
            $completion_docs = $pre_condition_docs = $design_docs = $commercial_docs = [];
        $updatePreConstruction = $updateDesign = $updateCommercial = $updatePlanning =
            $updatePreStart = $updateSiteRecord = $updateCompletion = false;

        // Get active stage
        if ($project->status == PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS) {
            switch ($project->progress_stage) {
                case PROJECT_STAGE_PRE_CONSTRUCTION:
                    $updatePreConstruction = true;
                    break;
                case PROJECT_STAGE_DESIGN:
                    $updateDesign = true;
                    break;
                case PROJECT_STAGE_COMMERCIAL:
                    $updateCommercial = true;
                    break;
                case PROJECT_STAGE_PLANNING:
                    $updatePlanning = true;
                    break;
                case PROJECT_STAGE_PRE_START:
                    $updatePreStart = true;
                    break;
                case PROJECT_STAGE_SITE_RECORD:
                    $updateSiteRecord = true;
                    break;
                case PROJECT_STAGE_COMPLETION:
                    $updateCompletion = true;
                    break;
            }
        }

        // For contractor
        if(\Auth::user()->clients->client_type == 1) {
            $logged_user = \Auth::user();
            if ((!in_array($logged_user->client_id, explode(',', $project->checked_contractors))
                and !in_array($logged_user->client_id, explode(',', $project->contractors)))
                || ($project->status == 5 || $project->decommissioned == PROJECT_DECOMMISSION)
            ) {
                abort(401);
            }
            $canBeUpdateThisProject = true;
            $updateContractor = true;
        } else {
            // For CO1
            $canBeUpdateThisProject = true;
            $updateTender = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_TENDER_DOCUMENT);
            $updateContractor = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_CONTRACTOR_DOCUMENT);
            $updateGSK = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_OHS_DOCUMENT);
            // todo
            $updatePlanning = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_PLANNING_DOCUMENT) & $updatePlanning;
            $updatePreStart = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_PRESTART_DOCUMENT) & $updatePreStart;
            $updateSiteRecord = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_SITE_RECORD_DOCUMENT) & $updateSiteRecord;
            $updateCompletion = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_COMPLETION_DOCUMENT) & $updateCompletion;
            $updatePreConstruction = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_PRE_CONSTRUCTION_DOCUMENT) & $updatePreConstruction;
            $updateDesign = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_DESIGN_DOCUMENT) & $updateDesign;
            $updateCommercial = \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROJECT_INFO, JR_UPDATE_PROJECT_COMMERCIAL_DOCUMENT) & $updateCommercial;

            // check update permission for asbestos
            if (isset($project->projectType->compliance_type) and ($project->projectType->compliance_type == ASBESTOS_PROJECT)) {
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS )) {
                    $canBeUpdateThisProject = false;
                }
            }
            // check update permission for fire
            if (isset($project->projectType->compliance_type) and ($project->projectType->compliance_type == FIRE_PROJECT)) {
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_FIRE, JOB_ROLE_FIRE )) {
                    $canBeUpdateThisProject = false;
                }
            }
            // check update permission for water
            if (isset($project->projectType->compliance_type) and ($project->projectType->compliance_type == WATER_PROJECT)) {
                if (!\CompliancePrivilege::checkUpdatePermission(JR_UPDATE_PROPERTIES_INFOMATION, JR_UPDATE_PROJECT_WATER, JOB_ROLE_WATER )) {
                    $canBeUpdateThisProject = false;
                }
            }
        }

        $project_contractors = $this->projectRepository->getContractors($project->contractors);
        $project_surveys = $this->projectRepository->getProjectSurveys($project->survey_id);
        $project_assessments = $this->projectRepository->getProjectAssessments($project->assessment_ids);
        $project_document = $this->complianceCategoryDocumentRepository->getDocProject($project->document_ids);
        $hazard_project = $this->hazardRepository->getHazardsProject($project->hazard_ids);
        $linked_projects = $this->projectRepository->getLinkedProject($project->linked_project_id);

//        $tender_docs = $this->projectRepository->getTenderBoxDocs($project_id, TENDER_DOC_CATEGORY);
        $planning_docs = $this->projectRepository->getTenderBoxDocs($project_id, PLANNING_DOC_CATEGORY);
        $pre_start_docs = $this->projectRepository->getTenderBoxDocs($project_id, PRE_START_DOC_CATEGORY);
        $site_record_docs = $this->projectRepository->getTenderBoxDocs($project_id, SITE_RECORDS_DOC_CATEGORY);
        $completion_docs = $this->projectRepository->getTenderBoxDocs($project_id, COMPLETION_DOC_CATEGORY);
        $pre_construction_docs = $this->projectRepository->getTenderBoxDocs($project_id, PRE_CONSTRUCTION_DOC_CATEGORY);
        $design_docs = $this->projectRepository->getTenderBoxDocs($project_id, DESIGN_DOC_CATEGORY);
        $commercial_docs = $this->projectRepository->getTenderBoxDocs($project_id, COMMERCIAL_DOC_CATEGORY);


        if (!is_null($project_contractors)) {
            foreach ($project_contractors as $contractor) {
                $contractor->data = $this->projectRepository->getContractorBoxDocs($project_id, CONTRACTOR_DOC_CATEGORY, $contractor->id);
            }
        }

        if (!is_null($project_surveys)) {
            foreach ($project_surveys as $survey) {
                $survey->data = $this->projectRepository->getSurveyDocs($survey);
            }
        }

        $gsk_docs = $this->projectRepository->getTenderBoxDocs($project_id, GSK_DOC_CATEGORY);

        $contractor_document_types = $this->projectRepository->listDocumentTypes(CONTRACTOR_DOC_TYPE);
        $gsk_document_types = $this->projectRepository->listDocumentTypes(GSK_DOC_TYPE);
//        $tender_document_types = $this->projectRepository->listDocumentTypes(TENDER_DOC_TYPE);
        $planning_document_types = $this->projectRepository->listDocumentTypes(PLANNING_DOC_TYPE);
        $pre_start_document_types = $this->projectRepository->listDocumentTypes(PRE_START_DOC_TYPE);
        $site_record_document_types = $this->projectRepository->listDocumentTypes(SITE_RECORD_DOC_TYPE);
        $completion_document_types = $this->projectRepository->listDocumentTypes(COMPLETION_DOC_TYPE);
        $pre_construction_document_types = $this->projectRepository->listDocumentTypes(PRE_CONSTRUCTION_DOC_TYPE);
        $design_document_types = $this->projectRepository->listDocumentTypes(DESIGN_DOC_TYPE);
        $commercial_document_types = $this->projectRepository->listDocumentTypes(COMMERCIAL_DOC_TYPE);
        $is_approval_project =  $this->projectRepository->is_approval_project($project_id);
        $work_streams = $this->projectRepository->getWorkStreams();

        $locked = ($project->status > PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS) ? TRUE : FALSE;

        //log audit
        \CommonHelpers::logAudit(PROJECT_TYPE, $project->id, AUDIT_ACTION_VIEW, $project->reference,0 , null ,0 ,$project->property_id);
        return view('project.index',[
            'project' => $project,
            'project_contractors' => $project_contractors,
            'tender_docs' => $tender_docs,
            'planning_docs' => $planning_docs,
            'pre_start_docs' => $pre_start_docs,
            'site_record_docs' => $site_record_docs,
            'completion_docs' => $completion_docs,
            'pre_construction_docs' => $pre_construction_docs,
            'design_docs' => $design_docs,
            'commercial_docs' => $commercial_docs,
            'gsk_docs' => $gsk_docs,
            'project_surveys' => $project_surveys,
            'project_assessments' => $project_assessments,
            'hazard_project' => $hazard_project,
            'project_document' => $project_document,
            'contractor_document_types' => $contractor_document_types,
            'pre_construction_document_types' => $pre_construction_document_types,
            'design_document_types' => $design_document_types,
            'commercial_document_types' => $commercial_document_types,
            'gsk_document_types' => $gsk_document_types,
            'planning_document_types' => $planning_document_types,
            'pre_start_document_types' => $pre_start_document_types,
            'site_record_document_types' => $site_record_document_types,
            'completion_document_types' => $completion_document_types,
            'is_approval_project' => $is_approval_project,
            'locked' => $locked,
            'in_contractors' => $in_contractors,
            'in_checked_contractors' => $in_checked_contractors,
            'canBeUpdateThisProject' => $canBeUpdateThisProject,
            'updatePlanning' => $updatePlanning,
            'updatePreStart' => $updatePreStart,
            'updateSiteRecord' => $updateSiteRecord,
            'updateCompletion' => $updateCompletion,
            'updatePreConstruction' => $updatePreConstruction,
            'updateDesign' => $updateDesign,
            'updateCommercial' => $updateCommercial,
            'updateContractor' => $updateContractor,
            'work_streams' => $work_streams,
            'linked_projects' => $linked_projects
        ]);
    }

    public function getAddProject($property_id) {
        //check privilege
        if (!\CommonHelpers::isSystemClient()) {
            // abort(404);
        } else {
            if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $property_id)) {
                abort(403);
            }
        }

        $property = $this->propertyRepository->findWhere(['id'=> $property_id])->first();
        $project_classification = $this->projectRepository->getProjectClassification();
        $project_types = $this->projectRepository->getProjectTypes();
        $asbestos_leads = $this->clientRepository->getClientUsers(1);
        $sponsor_leads = $this->clientRepository->getClientUsers(1);
        $sponsors = $this->projectRepository->getSponsorList();
        $survey_types = $this->projectRepository->getSurveyTypes();
        $surveys = $this->propertyRepository->getPropertySurvey($property_id);
        $assessments = $this->propertyRepository->getPropertyAssessment($property_id);
        $linked_projects = $this->propertyRepository->getLinkedPropertyProject($property_id);
        $rr_conditions = $this->projectRepository->getRRConditions();
        $survey_only_contractors = $this->projectRepository->getSelectContractors(PROJECT_SURVEY_ONLY);
        $remendiation_contractors = $this->projectRepository->getSelectContractors(PROJECT_REMEDIATION_REMOVAL);
        $demolition_contractors = $this->projectRepository->getSelectContractors(PROJECT_DEMOLITION);
        $analytical_contractors = $this->projectRepository->getSelectContractors(PROJECT_ANALYTICAL);
        $work_streams = $this->projectRepository->getWorkStreams();
        $document_project = $this->complianceCategoryDocumentRepository->getDocProperty($property_id);
        $hazard_project = $this->hazardRepository->getHazardsProperty($property_id);

        return view('project.add_project',[
            'property' => $property,
            'project_types' => $project_types,
            'asbestos_leads' => $asbestos_leads,
            'sponsor_leads' => $sponsor_leads,
            'sponsors' => $sponsors,
            'survey_types' => $survey_types,
            'surveys' => $surveys,
            'project_classification' => $project_classification,
            'assessments' => $assessments,
            'rr_conditions' => $rr_conditions,
            'survey_only_contractors' => $survey_only_contractors,
            'remendiation_contractors' => $remendiation_contractors,
            'demolition_contractors' => $demolition_contractors,
            'analytical_contractors' => $analytical_contractors,
            'work_streams' => $work_streams,
            'linked_projects' => $linked_projects,
            'document_project' => $document_project,
            'hazard_project' => $hazard_project
        ]);
    }

    public function postAddProject($property_id, ProjectCreateRequest $projectCreateRequest) {
        $validatedData = $projectCreateRequest->validated();
        $projectCreate = $this->projectRepository->createProject($validatedData);
        if (isset($projectCreate) and !is_null($projectCreate)) {
            if ($projectCreate['status_code'] == 200) {
                return redirect()->route('project.index', ['project_id' => $projectCreate['data']->id ])->with('msg', $projectCreate['msg']);
            } else {
                return redirect()->back()->with('err', $projectCreate['msg']);
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
        $contractor_lists = $this->projectRepository->getSelectContractors($request->type_id);
        $html = view('forms.form_multiple_option', [ 'name'=>'contractors',
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

    public function getEditProject($project_id) {
        $project = $this->projectRepository->findWhere(['id'=> $project_id])->first();
        //check privilege

        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        } else {
            // if (!\CompliancePrivilege::checkPermission(PROPERTY_PERMISSION, $project->property_id) || !\CompliancePrivilege::checkUpdatePermission(PROJECTS_TYPE_UPDATE_PRIV, $project->project_type)) {
            //     abort(404);
            // }
        }

        $project_classification = $this->projectRepository->getProjectClassification();
        $project_types = $this->projectRepository->getProjectTypes();
        $asbestos_leads = $this->clientRepository->getClientUsers(1);
        $sponsor_leads = $this->clientRepository->getClientUsers(1);
        $sponsors = $this->projectRepository->getSponsorList();
        $survey_types = $this->projectRepository->getSurveyTypes();
        $surveys = $this->propertyRepository->getPropertySurvey($project->property_id);
        $assessments = $this->propertyRepository->getPropertyAssessment($project->property_id);
        $rr_conditions = $this->projectRepository->getRRConditions();
        $linked_projects = $this->propertyRepository->getLinkedPropertyProject($project->property_id, $project_id);

        $document_project = $this->complianceCategoryDocumentRepository->getDocProperty($project->property_id);
        $hazard_project = $this->hazardRepository->getHazardsProperty($project->property_id);

        $linked_survey = explode(",",$project->survey_id);
        $work_streams = $this->projectRepository->getWorkStreams();
        return view('project.edit_project',[
            'project' => $project,
            'project_types' => $project_types,
            'asbestos_leads' => $asbestos_leads,
            'sponsor_leads' => $sponsor_leads,
            'sponsors' => $sponsors,
            'survey_types' => $survey_types,
            'surveys' => $surveys,
            'project_classification' => $project_classification,
            'assessments' => $assessments,
            'rr_conditions' => $rr_conditions,
            'linked_survey' => $linked_survey,
            'work_streams' => $work_streams,
            'linked_projects' => $linked_projects,
            'document_project' => $document_project,
            'hazard_project' => $hazard_project
        ]);
    }

    public function postEditProject($project_id, ProjectCreateRequest $projectCreateRequest) {
        $validatedData = $projectCreateRequest->validated();
        $projectUpdate = $this->projectRepository->createProject($validatedData, $project_id);

        if (isset($projectUpdate) and !is_null($projectUpdate)) {
            if ($projectUpdate['status_code'] == 200) {
                return redirect()->route('project.index', ['project_id' => $projectUpdate['data'] ])->with('msg', $projectUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $projectUpdate['msg']);
            }
        }
    }

    public function postChangeProgress($project_id, Request $request)
    {
        $data = $request->all();
        $result = $this->projectRepository->changeProgress($project_id, $data);
        if ($result['status_code'] == 200) {
            return response()->json($result);
        } else {
            return response()->json([]);
        }
    }

    public function archiveProject($project_id, Request $request) {
        $type = $request->has('type') ? $request->type : '';
        $archiveProject = $this->projectRepository->archiveProject($project_id, $type);
        if (isset($archiveProject)) {
            if ($archiveProject['status_code'] == 200) {
                return redirect()->back()->with('msg', $archiveProject['msg']);
            } else {
                return redirect()->back()->with('err', $archiveProject['msg']);
            }
        }
    }

    public function searchProject(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->projectRepository->searchProjectAdminTool($query_string);
        return response()->json($data);
    }

    public function decommissionProject($project_id, Request $request){
        $decommission_reason = $request->decommission_reason ?? NULL;
        $decommissionProject = $this->projectRepository->decommissionProject($project_id, $decommission_reason);
        if (isset($decommissionProject)) {
            if ($decommissionProject['status_code'] == 200) {
                return redirect()->back()->with('msg', $decommissionProject['msg']);
            } else {
                return redirect()->back()->with('err', $decommissionProject['msg']);
            }
        }
    }
}
