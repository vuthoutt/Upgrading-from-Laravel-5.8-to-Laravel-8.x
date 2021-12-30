<?php

namespace App\Http\Controllers\ShineCompliance;

use App\Http\Controllers\Controller;
use App\Services\ShineCompliance\PropertyService;
use App\Services\ShineCompliance\ZoneService;
use App\Services\ShineCompliance\ClientService;
use App\Services\ShineCompliance\DepartmentService;
use App\Services\ShineCompliance\ContractorSetupService;
use App\Services\ShineCompliance\UserService;
use App\Services\ShineCompliance\RoleService;
use App\Services\ShineCompliance\JobRoleService;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Property;
use App\Models\Zone;
use App\Http\Request\ShineCompliance\Zone\ZoneRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Request\ShineCompliance\Client\UpdateOrganisationRequest;

class ClientController extends Controller
{
    private $zoneService;
    private $propertyService;
    private $summaryService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        ZoneService $zoneService,
        PropertyService $propertyService,
        ClientService $clientService,
        DepartmentService $departmentService,
        ContractorSetupService $contractorSetupService,
        JobRoleService $jobRoleService,
        UserService $userService,
        RoleService $roleService
    )
    {
        $this->zoneService = $zoneService;
        $this->propertyService = $propertyService;
        $this->clientService = $clientService;
        $this->departmentService = $departmentService;
        $this->contractorSetupService = $contractorSetupService;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->jobRoleService = $jobRoleService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index($client_id)
    {
//        if (\Auth::user()->client_id != $client_id) {
//            return redirect()->route('contractor', ['client_id' => $client_id]);
//        }
        $client = $this->clientService->getClient($client_id);
        $departments = $this->departmentService->getAllDepartmentClient('client');
        $departments_contractor = $this->departmentService->getAllDepartmentClient('contractor');
        $view_detail = true;
        $view_policy = true;
        $view_department = true;
        $view_traning_record = true;

        $edit_detail = true;
        $edit_policy = true;
        $edit_department = true;
        $edit_traning_record = true;
        //log audit
        \CommonHelpers::logAudit(ORGANISATION_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference);
        return view('shineCompliance.client.index',[
            'client' => $client,
            'departments' => $departments,
            'departments_contractor' => $departments_contractor,
            'view_detail' => $view_detail,
            'view_policy' => $view_policy,
            'view_department' => $view_department,
            'view_traning_record' => $view_traning_record,
            'edit_detail' => $view_detail,
            'edit_policy' => $view_policy,
            'edit_department' => $view_department,
            'edit_traning_record' => $view_traning_record,
        ]);
    }

    public function getEditOrganisation($client_id, Request $request) {

        $client = $this->clientService->getClient($client_id);
        $type = $request->type ?? '';
        if (empty($client)) {
            abort(404);
        }
        if (!\CommonHelpers::isSystemClient()) {
            if ($type == CONTRACTOR_TYPE) {
                abort(404);
            }
        } else {
            // if (!\CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_DETAILS) and ($type == CONTRACTOR_TYPE)) {
            //     abort(404);
            // }
        }

        $contractor_setup = $this->contractorSetupService->getAllContractorSetup();

        //log audit
        $comment = \Auth::user()->full_name . " viewed organisation to edit";
        \ComplianceHelpers::logAudit(ORGANISATION_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference, 0, $comment);
        return view('shineCompliance.client.edit_organisation', ['client' => $client, 'type' => $type, 'contractor_setup' => $contractor_setup]);
    }

    public function postEditOrganisation($client_id, UpdateOrganisationRequest $updateOrganisationRequest) {
        $validatedData = $updateOrganisationRequest->validated();
        $clientUpdate = $this->clientService->updateOrCreateContractor($validatedData, $client_id);

        if (isset($clientUpdate) and !is_null($clientUpdate)) {
            if ($clientUpdate['status_code'] == 200) {
                return redirect()->route('shineCompliance.my_organisation', ['client_id' => $client_id])->with('msg', $clientUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $clientUpdate['msg']);
            }
        }
    }

    public function departmentUsers($department_id, Request $request) {
        $type = $request->type;
        $client_id = $request->client_id;
        $department = $this->departmentService->getDepartment('client', $department_id);

        $users = $this->clientService->getDepartmentUsers($client_id, $department_id);
        $client = $this->clientService->getFindClient($client_id);

        $department->client = $client;

        $view_department = true;
        $edit_department = true;

        if (\CommonHelpers::isSystemClient()) {
            // $view_client_list = \CompliancePrivilege::getPermission(CONTRACTOR_PERMISSION);
            // if (!in_array($client_id, $view_client_list)) {
            //     abort(404);
            // }

            $view_department = \CompliancePrivilege::getContractorPermission($client_id, CONTRACTOR_DEPARTMENTS);

            $edit_department = \CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_DEPARTMENTS);

        }

        //log audit
        $comment = \Auth::user()->full_name . " viewed department users of client " . ($client->name ?? '');
        \ComplianceHelpers::logAudit(ORGANISATION_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference, 0, $comment);
            return view('shineCompliance.client.index',['department' => $department,
                'users' => $users,
                'type_view' => 'department_users',
                'department_id' => $department_id,
                'client_id' => $client_id,
                'client' => $client,
                'view_department' => $view_department,
                'edit_department' => $edit_department
            ]);
//        }
    }

    public function getAddUser($client_id, $department_id, Request $request){
        $type = $request->type;
        $user = \Auth::user();
        $getDepartments = $this->userService->getListSameLevelDepartmentClient($department_id, $type, $client_id);
        $client = $this->clientService->getClient($client_id);
        $roleSelect = $this->jobRoleService->getListJobRoles();

        // $roleSelect = json_decode(json_encode($roleSelect));

        //log audit
        $comment = \Auth::user()->full_name . " viewed add user form of client " . ($client->name ?? '');
        \ComplianceHelpers::logAudit(ORGANISATION_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference, 0, $comment);
        return view('shineCompliance.client.add_user',[
            'departments' => $getDepartments['data'],
            'type' => $type,
            'client' => $client,
            'department_id' => $department_id,
            'roleSelect' => $roleSelect,
        ]);
    }

    public function postAddUser(UserCreateRequest $userCreateRequest) {
        $data = $userCreateRequest->validated();
        $createUser = $this->userService->createContractorUser($data);
        if (isset($createUser) and !is_null($createUser)) {
            if ($createUser['status_code'] == 200) {
                return redirect()->route('profile', ['user' => $createUser['data']])->with('msg', $createUser['msg']);
            } else {
                return redirect()->back()->with('err', $createUser['msg']);
            }
        }
    }

    public function contractor($client_id) {

        $view_detail = true;
        $view_policy = true;
        $view_department = true;
        $view_traning_record = true;

        $edit_detail = true;
        $edit_policy = true;
        $edit_department = true;
        $edit_traning_record = true;
        if (\CommonHelpers::isSystemClient()) {

            $view_detail = \CompliancePrivilege::getContractorPermission($client_id, CONTRACTOR_DETAILS);
            $view_policy = \CompliancePrivilege::getContractorPermission($client_id, CONTRACTOR_POLICY_DOCUMENTS);
            $view_department = \CompliancePrivilege::getContractorPermission($client_id, CONTRACTOR_DEPARTMENTS);
            $view_traning_record = \CompliancePrivilege::getContractorPermission($client_id, CONTRACTOR_TRAINING_RECORDS);

            $edit_detail = \CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_DETAILS);
            $edit_policy = \CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_POLICY_DOCUMENTS);
            $edit_department = \CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_DEPARTMENTS);
            $edit_traning_record = \CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_TRAINING_RECORDS);

        }

        $client = $this->clientService->getClient($client_id);
        $departments = $this->departmentService->getAllDepartmentClient($client_id);
        $departments_contractor = $this->departmentService->getAllDepartmentClient('contractor', $client_id);

        //log audit
        \CommonHelpers::logAudit(CONTRACTOR_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference);
        return view('shineCompliance.client.contractor',[
            'client' => $client,
            'departments' => $departments,
            'departments_contractor' => $departments_contractor,
            'view_detail' => $view_detail,
            'view_policy' => $view_policy,
            'view_department' => $view_department,
            'view_traning_record' => $view_traning_record,
            'edit_detail' => $edit_detail,
            'edit_policy' => $edit_policy,
            'edit_department' => $edit_department,
            'edit_traning_record' => $edit_traning_record
        ]);
    }

    public function allContractors() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        //privilege
        $view_client_list = \CompliancePrivilege::getPermission(CONTRACTOR_PERMISSION);

        $client_lists = $this->clientService->getClientPrivilege();
        $trainning_records = $this->clientService->getTraningRecord(ALL_CLIENT_TRAINING_ID);
        return view('shineCompliance.client.all_contractors',[
            'client_lists' => $client_lists,
            'trainning_records' => $trainning_records,
        ]);
    }

    public function getClientUsers($client_id){
        $users = $this->clientService->getClientUsers($client_id);
        return $users;
    }

    public function getClientUsersAssessment($client_id){
        $users = $this->clientService->getClientUsersAssessment($client_id);
        return $users;
    }

    public function getClientAssessment($client_id){
        $users = $this->clientService->getClientAssessment($client_id);
        return $users;
    }

    public function getClientLeadsAssessment($client_id){
        $users = $this->clientService->getClientLeadsAssessment($client_id);
        return $users;
    }
}
