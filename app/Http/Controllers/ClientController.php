<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\SummaryRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\UserRepository;
use App\Models\TrainingRecord;
use App\Services\ShineCompliance\JobRoleService;
use App\Models\Client;
use App\Models\Role;
use App\Http\Request\Client\UpdateOrganisationRequest;
use App\Http\Request\Client\CreateContractorRequest;
use App\Http\Request\Client\UserCreateRequest;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClientRepository $clientRepository,
        DepartmentRepository $departmentRepository,
        JobRoleService $jobRoleService,
        SummaryRepository $summaryRepository,
        UserRepository $userRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->summaryRepository = $summaryRepository;
        $this->departmentRepository = $departmentRepository;
        $this->jobRoleService = $jobRoleService;
    }

    /**
     * Show my organisation by id.
     *
     */
    public function index($client_id)
    {
        if (\Auth::user()->client_id != $client_id) {
            return redirect()->route('contractor', ['client_id' => $client_id]);
        }
        $client = $this->clientRepository->getClient($client_id);
        $departments = $this->summaryRepository->getAllDepartmentClient('client', $client_id);
        $departments_contractor = $this->summaryRepository->getAllDepartmentClient('contractor', $client_id);
        $view_detail = true;
        $view_policy = true;
        $view_department = true;
        $view_traning_record = true;

        $edit_detail = true;
        $edit_policy = true;
        $edit_department = true;
        $edit_traning_record = true;

        if (\CommonHelpers::isSystemClient()) {

            $view_detail = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DETAILS);
            $view_policy = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_POLICY_DOCUMENTS);
            $view_department = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DEPARTMENTS);
            $view_traning_record = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_TRAINING_RECORDS);

            $edit_detail = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DETAILS);
            $edit_policy = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_POLICY_DOCUMENTS);
            $edit_department = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DEPARTMENTS);
            $edit_traning_record = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_TRAINING_RECORDS);
        }

        //log audit
        \CommonHelpers::logAudit(ORGANISATION_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference);
        return view('client.index',[
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
            'edit_traning_record' => $edit_traning_record,
        ]);
    }

    /**
     * get edit organisation form by id.
     *
     */
    public function getEditOrganisation($client_id, Request $request) {
        $client = $this->clientRepository->getClient($client_id);
        $type = $request->type;
        if (empty($client)) {
            abort(404);
        }
        // if (!\CommonHelpers::isSystemClient()) {
        //     if ($type == CONTRACTOR_TYPE) {
        //         abort(404);
        //     }
        // } else {
        //     if (!\CompliancePrivilege::getContractorUpdatePermission($client_id, CONTRACTOR_DETAILS) and ($type == CONTRACTOR_TYPE)) {
        //         abort(404);
        //     }
        // }

        return view('client.edit_organisation',['client' => $client, 'type' => $type]);
    }

    /**
     *update organisation.
     *
     */
    public function postEditOrganisation($client_id, UpdateOrganisationRequest $updateOrganisationRequest) {
        $validatedData = $updateOrganisationRequest->validated();

        $clientUpdate = $this->clientRepository->updateOrCreateContractor($validatedData, $client_id);

        if (isset($clientUpdate) and !is_null($clientUpdate)) {
            if ($clientUpdate['status_code'] == 200) {
                if ($validatedData['type'] == CONTRACTOR_TYPE) {
                    return redirect()->route('contractor', ['client_id' => $client_id])->with('msg', $clientUpdate['msg']);
                } else {
                    return redirect()->route('my_organisation', ['client_id' => $client_id])->with('msg', $clientUpdate['msg']);
                }
            } else {
                return redirect()->back()->with('err', $clientUpdate['msg']);
            }
        }
    }

    public function departmentUsers($department_id, Request $request) {
        $type = $request->type;
        $client_id = $request->client_id;
        if ($type == CONTRACTOR_TYPE) {
            $department = $this->summaryRepository->getDepartment('contractor', $department_id);
            $permission = 'contractor';
        } else {
            $department = $this->summaryRepository->getDepartment('client', $department_id);
            $permission = 'organisation';
        }

        $users = $this->clientRepository->getDepartmentUsers($client_id, $department_id);
        $client = Client::find($client_id);
        $department->client = $client;

        $view_department = true;
        $edit_department = true;

        if (\CommonHelpers::isSystemClient()) {


            $view_department = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DEPARTMENTS, $permission);

            $edit_department = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DEPARTMENTS, $permission);

        }
        if ($request->type == 'contractor') {
            return view('client.contractor',['department' => $department,
                                            'users' => $users,
                                            'type_view' => 'department_users',
                                            'department_id' => $department_id,
                                            'client_id' => $client_id,
                                            'client' => $client,
                                            'view_department' => $view_department,
                                            'edit_department' => $edit_department,
                                        ]);
        } elseif ($type == CLIENT_TYPE) {
            return view('client.client_detail',['department' => $department,
                            'users' => $users,
                            'view' => 'department_users',
                            'department_id' => $department_id,
                            'client_id' => $client_id,
                            'data' => $client,
                            'view_department' => $view_department,
                            'edit_department' => $edit_department,
                        ]);
        } {
            return view('client.index',['department' => $department,
                                        'users' => $users,
                                        'type_view' => 'department_users',
                                        'department_id' => $department_id,
                                        'client_id' => $client_id,
                                        'client' => $client,
                                        'view_department' => $view_department,
                                        'edit_department' => $edit_department
                                        ]);
        }
    }

    public function allContractors() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        //privilege
        if (\CommonHelpers::isSystemClient()) {
            $client_ids = \CompliancePrivilege::getContractorIdPermission('contractor');
            $client_lists = Client::where('client_type',1)->whereIn('id', $client_ids)->get();
        } else {
            $client_lists = Client::where('client_type',1)->get();
        }

        $trainning_records = TrainingRecord::where('client_id', ALL_CLIENT_TRAINING_ID)->get();
        return view('client.all_contractors',[
            'client_lists' => $client_lists,
            'trainning_records' => $trainning_records,
        ]);
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
            // $view_client_list = \CompliancePrivilege::getPermission(CONTRACTOR_PERMISSION);
            // if (!in_array($client_id, $view_client_list)) {
            //     abort(404);
            // }
            $view_detail = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DETAILS,'contractor');
            $view_policy = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_POLICY_DOCUMENTS,'contractor');
            $view_department = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DEPARTMENTS,'contractor');
            $view_traning_record = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_TRAINING_RECORDS,'contractor');

            $edit_detail = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DETAILS,'contractor');
            $edit_policy = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_POLICY_DOCUMENTS,'contractor');
            $edit_department = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DEPARTMENTS,'contractor');
            $edit_traning_record = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_TRAINING_RECORDS,'contractor');
        }

        $client = $this->clientRepository->getClient($client_id);
        $departments = $this->summaryRepository->getAllDepartmentClient('client', $client_id);
        $departments_contractor = $this->summaryRepository->getAllDepartmentClient('contractor', $client_id);

        //log audit
        \CommonHelpers::logAudit(CONTRACTOR_AUDIT_TYPE, $client->id, AUDIT_ACTION_VIEW, $client->reference);
        return view('client.contractor',[
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

    public function getAddContractor() {
        if (!\CommonHelpers::isSystemClient()) {
            abort(404);
        }
        return view('client.add_contractor');
    }

    public function postAddContractor(CreateContractorRequest $createContractorRequest) {
        $validatedData = $createContractorRequest->validated();
        $clientCreate = $this->clientRepository->updateOrCreateContractor($validatedData);
        if (isset($clientCreate) and !is_null($clientCreate)) {
            if ($clientCreate['status_code'] == 200) {
                return redirect()->route('contractor', ['client_id' => $clientCreate['data']])->with('msg', $clientCreate['msg']);
            } else {
                return redirect()->back()->with('err', $clientCreate['msg']);
            }
        }
    }

    public function getAddUser($client_id, $department_id, Request $request){
        $type = $request->type;
        $user = \Auth::user();
        $getDepartments = $this->userRepository->getListSameLevelDepartmentClient($department_id, $type, $client_id);
        $client = $this->clientRepository->getClient($client_id);
        $roleSelect = $this->jobRoleService->getListJobRoles();

        return view('client.add_user',[
            'departments' => $getDepartments['data'],
            'type' => $type,
            'client' => $client,
            'department_id' => $department_id,
            'roleSelect' => $roleSelect,
        ]);
    }

    public function postAddUser(UserCreateRequest $userCreateRequest) {
        $data = $userCreateRequest->validated();
        $createUser = $this->userRepository->createContractorUser($data);
        if (isset($createUser) and !is_null($createUser)) {
            if ($createUser['status_code'] == 200) {
                return redirect()->route('profile', ['user' => $createUser['data']])->with('msg', $createUser['msg']);
            } else {
                return redirect()->back()->with('err', $createUser['msg']);
            }
        }
    }

    public function clientDetail($client_id) {
        $view_detail = true;
        $view_policy = true;
        $view_department = true;
        $view_traning_record = true;

        $edit_detail = true;
        $edit_policy = true;
        $edit_department = true;
        $edit_traning_record = true;
        if (\CommonHelpers::isSystemClient()) {
            // $view_client_list = \CompliancePrivilege::getPermission(CONTRACTOR_PERMISSION);
            // if (!in_array($client_id, $view_client_list)) {
            //     abort(404);
            // }
            $view_detail = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DETAILS,'client');
            $view_policy = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_POLICY_DOCUMENTS,'client');
            $view_department = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_DEPARTMENTS,'client');
            $view_traning_record = \CompliancePrivilege::getContractorPermission($client_id, JR_CONTRACTOR_TRAINING_RECORDS,'client');


            $edit_detail = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DETAILS,'client');
            $edit_policy = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_POLICY_DOCUMENTS,'client');
            $edit_department = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_DEPARTMENTS,'client');
            $edit_traning_record = \CompliancePrivilege::getContractorUpdatePermission($client_id, JR_CONTRACTOR_TRAINING_RECORDS,'client');
        }

        $data = $this->clientRepository->getClient($client_id);
        if (is_null($data)) {
            abort(404);
        }
        $departments = $this->summaryRepository->getAllDepartments('client', $client_id);
        $departments_contractor = $this->summaryRepository->getAllDepartments('contractor', $client_id);
        //log audit
        \CommonHelpers::logAudit(CLIENT_TYPE,$client_id, AUDIT_ACTION_VIEW, $data->reference);
        return view('client.client_detail',[
            'data' => $data,
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
    public function clientList(){

        if (\CommonHelpers::isSystemClient()) {
            $client_ids = \CompliancePrivilege::getContractorIdPermission('client');
            $clients = Client::where('client_type',2)->whereIn('id', $client_ids)->get();
        } else {
            $clients = Client::where('client_type',2)->get();
        }

        return view('client.client_list',[
            'clients' => $clients
        ]);
    }

    public function getAddClient(){
        return view('client.add_client');
    }

    public function postAddClient(CreateContractorRequest $createContractorRequest){
        $validatedData = $createContractorRequest->validated();
        // create client
        $clientCreate = $this->clientRepository->updateOrCreateContractor($validatedData, null, 2);
        if (isset($clientCreate) and !is_null($clientCreate)) {
            if ($clientCreate['status_code'] == 200) {
                return redirect()->route('client.detail', ['client_id' => $clientCreate['data']])->with('msg', $clientCreate['msg']);
            } else {
                return redirect()->back()->with('err', $clientCreate['msg']);
            }
        }
    }

    public function getEditClient($client_id){
        $client = Client::find($client_id);
        if (is_null($client)) {
            abort(404);
        }
        return view('client.edit_client',[
            'client' => $client
        ]);
    }

    public function postEditClient($client_id, UpdateOrganisationRequest $updateOrganisationRequest){
        $validatedData = $updateOrganisationRequest->validated();
        // create client
        $updateClient = $this->clientRepository->updateOrCreateContractor($validatedData, $client_id, 2);
        if (isset($updateClient) and !is_null($updateClient)) {
            if ($updateClient['status_code'] == 200) {
                return redirect()->route('client.detail', ['client_id' => $client_id])->with('msg', $updateClient['msg']);
            } else {
                return redirect()->back()->with('err', $updateClient['msg']);
            }
        }
    }
}
