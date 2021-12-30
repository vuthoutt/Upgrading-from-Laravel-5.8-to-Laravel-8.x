<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Http\Request\ShineCompliance\User\UserUpdateRequest;
use App\Http\Request\ShineCompliance\User\ChangePasswordRequest;
use App\Models\Department;
use App\Models\DepartmentContractor;
use App\Models\Role;
use App\Repositories\ProjectRepository;
use App\Repositories\WorkRequestRepository;
use App\Services\ShineCompliance\ChartService;
use App\Services\ShineCompliance\DocumentService;
use App\Services\ShineCompliance\IncidentReportService;
use App\Services\ShineCompliance\SurveyService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\UserRepository;
use App\Services\ShineCompliance\UserService;
use App\Services\ShineCompliance\AssessmentService;
use App\Services\ShineCompliance\RoleService;
use App\Services\ShineCompliance\JobRoleService;
use App\Services\ShineCompliance\DepartmentService;

class UserController extends Controller
{
    private $chartService;
    private $workRequestRepository;
    private $incidentReportService;
    private $userService;
    private $assessmentService;
    private $roleService;
    private $departmentService;
    private $jobRoleService;
    private $surveyService;
    private $documentService;
    private $projectRepository;

    public function __construct(UserService $userService, RoleService $roleService,
                                departmentService $departmentService, AssessmentService $assessmentService,
                                JobRoleService $jobRoleService, ChartService $chartService,
                                WorkRequestRepository $workRequestRepository, IncidentReportService $incidentReportService,
                                SurveyService $surveyService, DocumentService $documentService,
                                ProjectRepository $projectRepository)
    {
        $this->userService = $userService;
        $this->assessmentService = $assessmentService;
        $this->roleService = $roleService;
        $this->departmentService = $departmentService;
        $this->jobRoleService = $jobRoleService;
        $this->chartService = $chartService;
        $this->workRequestRepository = $workRequestRepository;
        $this->incidentReportService = $incidentReportService;
        $this->surveyService = $surveyService;
        $this->documentService = $documentService;
        $this->projectRepository = $projectRepository;
    }

    public function login()
    {
        return redirect()->route('shineCompliance.zone_map');
    }

    public function index()
    {
        if(\Auth::user()->is_site_operative == 1) {
            return redirect()->route('zone.operative', ['client_id' => 1]);
        }
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $data_document_management = $this->chartService->createDocumentManagementChart(NULL);
        $data_quality_assurance = $this->chartService->createQualityAssuranceChart(NULL);
        //log audit
//        $comment = \Auth::user()->full_name  . " view user " . $data_user->full_name;
//        \CommonHelpers::logAudit(USER_TYPE, $data_user->id, AUDIT_ACTION_VIEW, $data_user->shine_reference, $data_user->client_id);
        return view('shineCompliance.user.dashboard',
            compact('data', 'data_document_management', 'data_quality_assurance')
        );
    }
    public function assessmentUser() {
        $risk_color = "grey";

        $waters = $this->assessmentService->listWaterRiskAssessor();
        $fires = $this->assessmentService->listFireRiskAssessor();
        $hs = $this->assessmentService->listHSAssessor();
        $user_id = \Auth::user()->id;
        $data_user = $this->userService->getUsers($user_id);

        return view('shineCompliance.user.assessment',[
            'data' => $data_user,
            'fires' => $fires,
            'waters' => $waters,
            'hs' => $hs,
        ]);
    }
    public function profile($id)
    {
        $data_user = $this->userService->getUsers($id);
        //log audit
//        $comment = \Auth::user()->full_name  . " view user " . $data_user->full_name;
//        \CommonHelpers::logAudit(USER_TYPE, $data_user->id, AUDIT_ACTION_VIEW, $data_user->shine_reference, $data_user->client_id);
        return view('shineCompliance.user.profile',
            ['data' => $data_user]
        );
    }
    public function getEditProfileCompliance($user_id)
    {
        $user = $this->userService->getUsers($user_id);
        if (is_null($user)) {
            abort(404);
        }
        $role_select = $this->jobRoleService->getListJobRoles();
        $departments = $this->departmentService->checkDataUser($user);
        $departments_data = $this->userService->getDepartment($user, $departments);

        return view('shineCompliance.user.edit',['userData' => $user,'departments' => $departments_data['data'], 'roleSelect' => $role_select]);
    }

    public function postEditProfileCompliance( UserUpdateRequest $userUpdateRequest, $id) {

        $validatedData = $userUpdateRequest->validated();
        $userUpdate = $this->userService->updateUser($id, $validatedData);
        if (isset($userUpdate) and !is_null($userUpdate)) {
            if ($userUpdate['status_code'] == 200) {
                return redirect()->route('shineCompliance.profile-shineCompliance', ['user' => $id])->with('msg', $userUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $userUpdate['msg']);
            }
        }
    }

    public function getChangePassword($id) {
        $user = $this->userService->getUsers($id);
        if (is_null($user)) {
            abort(404);
        }
        return view('shineCompliance.user.change_password',['userData' => $user]);
    }

    public function postChangePassword($id,ChangePasswordRequest $changePasswordRequest) {
        $validatedData = $changePasswordRequest->validated();

        $first_login = isset($validatedData['first_login']) ? true : false;
        $updatePassword = $this->userService->changePassword($validatedData, $id, $first_login);
        if (isset($updatePassword) and !is_null($updatePassword)) {
            if ($updatePassword['status_code'] == 200) {
                return redirect()->route('profile', ['user' => $id])->with('msg', $updatePassword['msg']);
            } else {
                return redirect()->back()->with('err', $updatePassword['msg']);
            }
        }
    }

    public function lock($id) {
        $lockUser = $this->userService->lockUser($id);

        if (isset($lockUser) and !is_null($lockUser)) {
            if ($lockUser['status_code'] == 200) {
                return redirect()->back()->with('msg', $lockUser['msg']);
            } else {
                return redirect()->back()->with('err', $lockUser['msg']);
            }
        }
    }

    public function zoneMap()
    {
        return view('shineCompliance.zones.zone_map_child');
    }

    public function property()
    {
        return view('shineCompliance.property.property_properties');
    }

    public function propertyDetail($id)
    {
        return view('shineCompliance.property.detail');
    }

    public function propertyRegister($id, $type = null)
    {
        return view('shineCompliance.property.register', ['type' => $type]);
    }

    public function propertyRegisterType($id, $type, $summaryType)
    {
        return view('shineCompliance.property.register_summary', ['type' => $type, 'summaryType' => $summaryType]);
    }

    public function addProperty($type)
    {
        return view('shineCompliance.property.add_property',
            ['type'=> $type]);
    }
    public function propertyAovSystems($id)
    {
        return view('shineCompliance.property.systems.aov_systems');
    }
    public function propertyAovSystemsProgrammes($id)
    {
        return view('shineCompliance.property.systems.systems_programmes');
    }
    public function propertyAovSystemsEquipment($id)
    {
        return view('shineCompliance.property.systems.systems_equipment');
    }

    public function propertyEquipment($id)
    {
        return view('shineCompliance.property.equipment');
    }

    public function propertyFireExit($id)
    {
        return view('shineCompliance.property.fireExit');
    }

    public function propertyParking($id)
    {
        return view('shineCompliance.property.parking');
    }

    public function propertySurvey($id, $type)
    {
        return view('shineCompliance.property.survey', ['type' => $type]);
    }

    public function propertyDrawing($id)
    {
        return view('shineCompliance.property.drawings.index');
    }

    public function propertyDrawingViewer($id)
    {
        return view('shineCompliance.property.drawings.viewCAD');
    }

    public function propertyDocumnet($id)
    {
        return view('shineCompliance.property.document');
    }

    public function subProperty($type)
    {
        return view('shineCompliance.property.sub_property',
            ['type'=> $type]);
    }

    public function item()
    {
        return view('shineCompliance.item.item');
    }

    public function hazardItem($type)
    {
        return view('shineCompliance.item.hazardItem', ['type' => $type]);
    }

    public function organisation()
    {
        return view('shineCompliance.organisation.organisation');
    }

    public function summary()
    {
        return view('shineCompliance.summary.summary');
    }

    public function dataCentre()
    {
        return view('shineCompliance.dataCentre.dashboard');
    }

    public function reportSummary()
    {
        return view('shineCompliance.reporting.summary');
    }

    public function room($id)
    {
        return view('shineCompliance.property.room.room');
    }

    public function addRoom()
    {
        return view('shineCompliance.property.room.add_location');
    }

    public function searchDocument(Request $request)
    {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = [
            0 => ['name'=> 'AOV System #1'],
            1 => ['name'=> 'Emergency Lighting System #1'],
            2 => ['name'=> 'Fall and Arrest System #1'],
            3 => ['name'=> 'Fire Detection & Alarm System #1'],
            4 => ['name'=> 'Lightening Protection System #1'],
            5 => ['name'=> 'Mains Electric System #1'],
            6 => ['name'=> 'Pat #1'],
        ];
        return response()->json($data);
    }
    public function adminToolBox()
    {
        return view('shineCompliance.resources.admin_tool_box.upload');
    }
    public function exportSummary()
    {
        $pathToFile = Storage::download('Example Export.csv');
        return $pathToFile;
    }

    public function getApproval()
    {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $works = $this->workRequestRepository->getListWorkWaitingForApproval($user_id);
        $asbestos_lead_admin = \CommonHelpers::getGetAdminAsbestosLead();
        $approvalAssessments = $this->assessmentService->getApprovalAssessment($user_id);
        $approvalSurveys = $this->surveyService->getApprovalSurvey($user_id);
        $approvalProjectDocs = $this->documentService->getListUserProjectDocuments(PROJECT_DOC_PUBLISHED);
        $approvalIncident = $this->incidentReportService->getUserApprovalIncident();
        return view('shineCompliance.user.approval', compact('data', 'approvalAssessments', 'approvalSurveys', 'approvalProjectDocs', 'asbestos_lead_admin','approvalIncident','works'));
    }

    public function getRejected()
    {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $assessments = $this->assessmentService->getRejectedAssessments($user_id);
        $surveys = $this->surveyService->getRejectedSurvey($user_id);
        $projectDocs = $this->documentService->getListUserProjectDocuments(DOCUMENT_STATUS_REJECTED);
        $rejectIncident = $this->incidentReportService->getUserRejectIncident();
        $works = $this->workRequestRepository->getListWorkRejected($user_id);

        return view('shineCompliance.user.rejected', compact('data', 'assessments','surveys', 'projectDocs','rejectIncident','works'));
    }

    public function getIncidentReports()
    {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $incident_reports = $this->incidentReportService->getIncidentReportsUser($user_id);
        $decommissioned_incident_reports = $this->incidentReportService->getIncidentReportsUser($user_id, INCIDENT_DECOMMISSIONED);

        return view('shineCompliance.user.incident_reporting', compact('data', 'incident_reports', 'decommissioned_incident_reports'));
    }

    public function certificate() {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        return view('shineCompliance.user.certificate', compact('data'));
    }

    public function projects() {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $myProjects = $this->projectRepository->getMyProjects(\Auth::user()->id, \Auth::user()->client_id);
        return view('shineCompliance.user.project', compact('data', 'myProjects'));
    }

    public function surveys() {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $client_id = \Auth::user()->client_id;
        $management_surveys = $this->surveyService->getSurveyByType($client_id, MANAGEMENT_SURVEY, $user_id);
        $refurbish_surveys = $this->surveyService->getSurveyByType($client_id, REFURBISHMENT_SURVEY, $user_id);
        $reinspection_surveys = $this->surveyService->getSurveyByType($client_id, RE_INSPECTION_REPORT, $user_id);
        $demolition_surveys = $this->surveyService->getSurveyByType($client_id, DEMOLITION_SURVEY, $user_id);
        $management_surveys_partial = $this->surveyService->getSurveyByType($client_id, MANAGEMENT_SURVEY_PARTIAL, $user_id);
        $sample_survey = $this->surveyService->getSurveyByType($client_id, SAMPLE_SURVEY, $user_id);
        return view('shineCompliance.user.survey', compact(
            'data',
            'management_surveys',
            'refurbish_surveys',
            'reinspection_surveys',
            'demolition_surveys',
            'management_surveys_partial',
            'sample_survey'
        ));
    }

    public function getWorkRequests() {
        $user_id = \Auth::user()->id;
        $data = $this->userService->getUsers($user_id);
        $asbestos = true;
        $fire = true;
        $water = true;
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS)){
            $asbestos = false;
        }
        if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_FIRE, JOB_ROLE_FIRE)){
            $fire = false;
        }
        if((\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_WATER, JOB_ROLE_WATER)) || !env('WATER_MODULE') ) {
            $water = false;
        }
        $asbestos_work_requests = $this->workRequestRepository->getListWorkLive(WORK_REQUEST_ASBESTOS_TYPE, $user_id);
        $fire_work_requests = $this->workRequestRepository->getListWorkLive(WORK_REQUEST_FIRE_TYPE, $user_id);
        return view('shineCompliance.user.work_requests', compact('data', 'asbestos', 'fire', 'water', 'asbestos_work_requests', 'fire_work_requests'));
    }
}
