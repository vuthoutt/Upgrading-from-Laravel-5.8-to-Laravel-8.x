<?php

namespace App\Services\ShineCompliance;

use App\Helpers\ComplianceHelpers;
use App\Jobs\SendEmail;
use App\Models\ShineCompliance\User;
use App\Repositories\ShineCompliance\UserRepository;
use App\Repositories\ShineCompliance\DepartmentRepository;
use App\Repositories\ShineCompliance\DepartmentContractorRepository;
use App\Repositories\ShineCompliance\RoleRepository;
use App\Repositories\ShineCompliance\ShineDocumentStorageRepository;
use App\Repositories\ShineCompliance\LogLoginRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\ShineCompliance\Contact;

use App\Repositories\ShineCompliance\JobRoleRepository;

class UserService{

    private $userRepository;

    public function __construct(UserRepository $userRepository, DepartmentRepository $departmentRepository,
                                DepartmentContractorRepository $departmentContractorRepository,
                                RoleRepository $roleRepository,
                                JobRoleRepository $jobRoleRepository,
                                ShineDocumentStorageRepository $shineDocumentStorageRepository,
                                LogLoginRepository $logLoginRepository
    ){
        $this->userRepository = $userRepository;
        $this->departmentRepository = $departmentRepository;
        $this->departmentContractorRepository = $departmentContractorRepository;
        $this->roleRepository = $roleRepository;
        $this->shineDocumentStorageRepository = $shineDocumentStorageRepository;
        $this->logLoginRepository = $logLoginRepository;
        $this->jobRoleRepository = $jobRoleRepository;
    }


    public function getUsers($user_id){
        return $this->userRepository->getUsers($user_id);
    }

    public function getSurveyUsers() {
        $users = $this->userRepository->getSurveyUsers();
        return $users ?? [];
    }

    public function getWhereInUser($id){
        $users = $this->userRepository->getWhereInUser($id);
        return $users ?? [];
    }

    public function getAsbestosLeads() {
        return $this->userRepository->getAsbestosLeads();
    }

    public function getAllUsers() {
        return $this->userRepository->getAllUsers();
    }

    public function getAllIncidentReportingUsers() {
        return $this->userRepository->getAllIncidentReportingUsers();
    }

    public function getDepartment($user, $departments){

        $client_type = $user->clients->client_type ?? '';
        $client_id = $user->client_id ?? '';
        $department_id = $user->department_id ?? '';
        if(!$user->department_id || !$departments){
            if($client_type == 0){
                $departments = $this->departmentRepository->getDepartmentType("allChildrens", 0);
            }else{
                $departments = $this->departmentContractorRepository->getDepartmentsContractorType("allChildrens", 0 , 0);
            }
            $arr_temp[0]['selected'] = "";
            $arr_temp[0]['data'] = $departments;
           $result = ComplianceHelpers::successResponse('Get departments successful',$arr_temp);
        } else {
            $list_depart = [];
            if ($client_type == 0) {
                $departments = $this->departmentRepository->getDepartmentId($department_id);
                if($departments){
                    $arr_temp = $this->departmentRepository->getDepartmentType("allParents", $departments->parent_id);
                    $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $client_type));
                }
            } else {
                $departments = $this->departmentContractorRepository->getDepartmentContractorId($department_id);
                if($departments){
                    $arr_temp = $this->departmentContractorRepository->getDepartmentContractorParents($departments->parent_id);
                    $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $client_type));
                }
            }
            $result = ComplianceHelpers::successResponse('Get departments successful',$list_depart);
        }
        return $result;
    }

    public function getParentDepartment($data, $list_depart, $selected, $type){
        $arr_temp = [];
        $arr_temp['selected'] = $selected;
        $arr_temp['data'] = $data;
        $list_depart[] = $arr_temp;
        if(isset($data[0]) && $data[0]){
            $parent_id = $data[0]->parent_id;
            if($type === CONTRACTOR_TYPE || $type == 1){
                $departments = $this->departmentContractorRepository->getDepartmentContractorId($parent_id);
            } else {
                $departments = $this->departmentRepository->getDepartmentId($parent_id);
            }
            if($departments){
                if($type === CONTRACTOR_TYPE || $type == 1){
                    $arr_temp = $this->departmentContractorRepository->getDepartmentContractorParents($departments->parent_id);
                } else {
                    $arr_temp = $this->departmentRepository->getDepartmentType("allParents", $departments->parent_id);
                }
                return $this->getParentDepartment($arr_temp, $list_depart, $parent_id, $type);
            }
        }
        return $list_depart;
    }

    public function updateUser($id, $valData) {
        try{
            $user = $this->userRepository->getUsers($id);
            $userOldEmail = $user->email ?? '';
            $changeEmail = false;

            // convert datepicker to d/m/y format
            // $last_asbestos_training = Carbon::createFromFormat('d/m/Y', $data['asbestos-awareness']);
            // $last_shine_asbestos_training = Carbon::createFromFormat('d/m/Y', $data['shine-asbestos']);
            $role = $this->jobRoleRepository->getDetail($valData['role'] ?? 0, ['jobRoleViewValue']);
            $dataUserUpdate = [
                "username" => $valData['username'] ,
                "department_id" => end($valData['departments']),
                "first_name" => $valData['first-name'],
                "last_name" => $valData['last-name'],
                "notes" => $valData['notes'],
                "role" => $valData['role'],
                "is_admin" => (isset($valData['is-admin'])) ? 1 : 0,
                "housing_officer" => (isset($valData['housing_officer'])) ? 1 : 0,
                "is_site_operative" => $role->jobRoleViewValue->general_is_operative ?? 0,
                // convert to timestamp
                "last_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($valData, 'asbestos-awareness')),
                "last_shine_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($valData, 'shine-asbestos')),
            ];

            if ($userOldEmail !== $valData['email']) {
                $dataUserUpdate['newEmail'] = $valData['email'];
                $changeEmail = true;
            }


        // convert datepicker to d/m/y format
        // $last_asbestos_training = Carbon::createFromFormat('d/m/Y', $data['asbestos-awareness']);
        // $last_shine_asbestos_training = Carbon::createFromFormat('d/m/Y', $data['shine-asbestos']);
        $role = $this->jobRoleRepository->getDetail($valData['role'] ?? 0);


            $dataContactUpdate = [
                'telephone' => $valData['telephone'],
                'mobile' => $valData['mobile'],
                'job_title' => $valData['job-title'],
            ];
            //save file
            if (isset($valData['signature'])) {
                $file = $valData['signature'];
                if (!is_null($file) and $file->isValid()) {
                    try {
                        $path = \CommonHelpers::getFileStoragePath($id, USER_SIGNATURE);
                        Storage::disk('local')->put($path, $file);
                        $condition_data =
                            [ 'object_id' => $id, 'type' => USER_SIGNATURE,];
                        $data_img = [
                            'path' => $path. $file->hashName(),
                            'fileName' => $file->hashName(),
                            'mime' => $file->getClientMimeType(),
                            'size' => $file->getSize(),
                            'addedBy' => \Auth::user()->id,
                            'addedDate' =>  Carbon::now()->timestamp,
                        ];
                        $this->shineDocumentStorageRepository->updateOrCreateStorage($condition_data, $data_img);
                    } catch (\Exception $e) {
                        dd($e);
                        return ComplianceHelpers::failResponse(STATUS_FAIL,'Failed to update signature. Please try again !');
                    }
                }
            }


            if (isset($valData['avatar'])) {
                $saveAvatarImage = \CommonHelpers::saveFileShineDocumentStorage($valData['avatar'], $id, AVATAR);
            }

            //update data
            $userUpdate = $this->userRepository->updateProfile($id,$dataUserUpdate);
            $contactUpdate = $user->contact()->update($dataContactUpdate);
            //send confirm email when user change email
            if ($changeEmail) {
                $userEmail = User::with('clients')->where('email', $userOldEmail)->first();
                $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($userEmail);
                $dataEmail = [
                    'username' => $userEmail->getFullNameAttribute(),
                    'domain' => \Config::get('app.url'),
                    'subject' => 'Profile Updated Notification',
                    'companyName' => $userEmail->clients->name,
                    'token' => $token,
                    'type' => 3,
                    'user_id' => $userEmail->id
                ];
                //log audit
                $comment = \Auth::user()->full_name  . " change email of user " . $user->full_name;
                ComplianceHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);

                //queue send email
                \Queue::pushOn(EMAIL_RESET_PASSWORD_QUEUE,new SendEmail($userOldEmail, $dataEmail,EMAIL_CHANGE_MAIL_QUEUE));

                return ComplianceHelpers::successResponse('Update user successfully, your new email is ready to change. Please check email to finish!');
            }
            //log audit
           $comment = \Auth::user()->full_name  . " edited user " . $user->full_name;
           \ComplianceHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_EDIT, $user->shine_reference, $user->client_id, $comment);
            return ComplianceHelpers::successResponse('Update user successfully!');


        } catch ( \Exception $e){
            dd($e);
            \Log::debug($e);
            return ComplianceHelpers::failResponse(STATUS_FAIL,'Have something wrong. Please try again !');
        }
    }

    public function createContractorUser($data) {

        try {
            $clientId = $data['client_id'];
            $username = trim($data['username']);
            $role = $this->jobRoleRepository->getDetail($data['role'] ?? 0);
            $dataUser = [
                "username" => $username ,
                "password" => \Hash::make($data['password']) ,
                "client_id" => $clientId,
                "department_id" => end($data['departments']),
                "first_name" => $data['first_name'] ?? '',
                "last_name" => $data['last_name'] ?? '',
                "email" => $data['email'],
                "role" => $data['role'] ?? 1,
                "is_site_operative" => $role->general_is_operative ?? 0,
                "joblead" => 0,
                "is_locked" => 0,
                "last_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'asbestos-awareness')),
                "last_shine_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'shine-asbestos')),
                "notes" => $data['notes'] ?? '',
                "is_lab" => $data['analyst_access'] ?? 0,
            ];

            //update data
            $user = $this->userRepository->createProfile($dataUser);
            // Check exist register
            $refUser = "ID" . $user->id;
            User::where('id', $user->id )->update(['shine_reference' => $refUser]);
            // create user contact
            $dataContact = [
                'user_id' => $user->id,
                'telephone' => $data['telephone'] ?? '',
                "mobile" => $data['mobile'] ?? '',
                'job_title' => $data['job-title'] ?? ''
            ];

            Contact::create($dataContact);

            // get linked equipment inventories
            $equipmentInventories = null;
            if ( isset($data['equipment_inventories']) and count($data['equipment_inventories']) > 0 ) {
                $equipmentInventories = array_unique($data['equipment_inventories']);
                foreach ($equipmentInventories as $equipmentInventory) {
                    if (!empty($equipmentInventory)) {
                        $dataEquipmentInventories[] = [
                            'user_id' => $user->id,
                            'equipment_inventory_id' => $equipmentInventory
                        ];
                    }
                }

                EquipmentInventoriesUsers::where(['user_id' => $user->id])->delete();
                if (isset($dataEquipmentInventories)) {
                    EquipmentInventoriesUsers::insert($dataEquipmentInventories);
                    //log audit
                    $comment = \Auth::user()->full_name  . " added new equipment inventories";
                    ComplianceHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
                }
            }

            //save signature
            if (isset($data['signature'])) {
                $saveAvatarImage = \CommonHelpers::saveFileShineDocumentStorage($data['signature'], $user->id, USER_SIGNATURE);
            }

            if (isset($data['avatar'])) {
                $saveAvatarImage = \CommonHelpers::saveFileShineDocumentStorage($data['avatar'], $user->id, AVATAR);
            }
            return \CommonHelpers::successResponse('Create new user successfully!', $user->id);
        } catch (\Exception $e) {
            return \CommonHelpers::failResponse(STATUS_FAIL,'Create User Fail. Please try again!');
        }

    }

    public function getClientUsers($client_id){
        return $this->userRepository->getClientUsers($client_id);
    }

    public function getListSameLevelDepartmentClient($department_id, $type, $client_id){
        $list_depart = [];
        if ($type != CONTRACTOR_TYPE) {
            $departments = $this->departmentRepository->getDepartmentId($department_id);
            if($departments){
                $arr_temp = $this->departmentRepository->getDepartmentType('allParents',$departments->parent_id);
                $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $type));
            }
        } else {
            $departments = $this->departmentContractorRepository->getDepartmentContractorId($department_id);
            if($departments){
//                $list_depart = DepartmentContractor::with('allParents')->where('parent_id', $departments->parent_id)->get();

                $arr_temp = $this->departmentContractorRepository->getDepartmentContractorParents('allParents',$departments->parent_id);
                $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $type));
            }
        }

        return \CommonHelpers::successResponse('Get departments successful',$list_depart);
    }

    public function changePassword($data, $id, $log_login = false) {
        $password = $data['password'];
        try {
            \DB::beginTransaction();
            $userUpdate = $this->userRepository->updateProfile($id,['password' => \Hash::make($password)]);
            $user = $this->userRepository->getFindUser($id);
            //log audit
            $comment = \Auth::user()->full_name  . " edited password of " . $user->full_name;
            \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_EDIT, $user->shine_reference, $user->client_id, $comment);
            if ($log_login) {
                $this->logLogin(true, $user->id, $user->username, $password);
            }
            \DB::commit();
            return \CommonHelpers::successResponse('Updated Password Successfully!');
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollBack();
            return \CommonHelpers::failResponse(STATUS_FAIL,'Update password fail !');
        }
    }

    public function logLogin($status, $user_id, $username,$password) {
        if ($status) {
            $dataLog = [
                'user_id' => $user_id,
                'logusername' => $username,
                'logpassword' => $password,
                'logip' => $_SERVER['REMOTE_ADDR'],
                'logtime' => time(),
                'success' => 1
            ];
        } else {
            $dataLog = [
                'user_id' => $user_id,
                'logusername' => $username,
                'logpassword' => 'Fail',
                'logip' => $_SERVER['REMOTE_ADDR'],
                'logtime' => time(),
                'success' => 0
            ];
        }

        $this->logLoginRepository->createLogLogin($dataLog);
        LogLogin::create($dataLog);
    }

    public function lockUser($id) {

        $user =  $this->userRepository->getFindUser($id);
        try {
            if ($user->is_locked == USER_LOCKED) {
                $this->userRepository->updateProfile($id,['is_locked' => USER_UNLOCKED]);
                //log audit
                $comment = \Auth::user()->full_name  . " unlocked user " . $user->full_name;
                \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
                return \CommonHelpers::successResponse('Unlock User successful!');
            } else {
                $this->userRepository->updateProfile($id,['is_locked' => USER_LOCKED]);
                //log audit
                $comment = \Auth::user()->full_name  . " locked user " . $user->full_name;
                \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
                return \CommonHelpers::successResponse('Lock User successful!');
            }
        } catch (\Exception $e) {
            \Log::debug($e);
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Lock User Fail!');
        }
    }
}
