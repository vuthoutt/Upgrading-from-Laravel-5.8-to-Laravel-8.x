<?php
namespace App\Repositories;
use App\Models\Client;
use App\Repositories\ShineCompliance\JobRoleRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use App\User;
use App\Models\Contact;
use App\Models\Department;
use App\Models\LogLogin;
use App\Models\DepartmentContractor;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\ShineDocumentStorage;
use App\Helpers\CommonHelpers;
use App\Repositories\ClientsRepository;
use App\Mail\ResetPasswordEmail;
use App\Mail\ChangeMailEmail;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmail;

class UserRepository extends BaseRepository {

    public function __construct(JobRoleRepository $jobRoleRepository
    ){
        $this->jobRoleRepository = $jobRoleRepository;
    }
    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return User::class;
    }

    /**
     * Login user
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function login($requests) {

        if (\Auth::attempt(['username' => $requests['username'], 'password' => $requests['password']])) {
            $user = \Auth::user();
            if ($user->is_locked == -1 || $user->is_locked == 1) {
                // if user is locked
                $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Your account is locked. Please contact the Shine Team.');
                \Auth::logout();
                //log login
                $this->logLogin(false, $user->id, $requests['username'], $requests['password']);
            } else {
                $check_log = LogLogin::where('user_id', \Auth::user()->id)->count();
                if ($check_log > 0) {
                    // login success
                    $response = CommonHelpers::successResponse('User Login Successful!',$user);
                    $this->logLogin(true, $user->id, $requests['username'], $requests['password']);
                } else {
                    $response = CommonHelpers::failResponse(401,'Please reset your password on first login.');
                }
                //log login
            }
        } else {
            $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Incorrect username or password. Please try again');
            //log login
            $this->logLogin(false, 0,  $requests['username'], $requests['password']);
        }
        return $response;
    }

    /**
     * Register new user
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function register($validatedData){
        // Create new department if pick other value
        $departmentID = $validatedData['department'];
        $clientId = $validatedData['organisation'];
        $client = Client::find($clientId);
        if ($validatedData['department'] == -1) {
            $dataDepartment = [
                'name' => $validatedData['department-other'],
                'client_id' => $clientId,
            ];
            if ($client->client_type == 0) {
                $departmentNew = Department::create($dataDepartment);
            } else {
                $departmentNew = DepartmentContractor::create($dataDepartment);
            }
            if (is_null($departmentNew)) {
                return CommonHelpers::failResponse(STATUS_FAIL,'Can not create new department. Please try again!');
            }
            $departmentID = $departmentNew->id;
        }
        $username = trim($validatedData['user-name']);
        $username = str_replace(' ','', $username);
        $role = Role::find($data['role'] ?? 0);
        $data = [
            "username" => $username ,
            "client_id" => $clientId,
            "department_id" => $departmentID,
            "first_name" => $validatedData['first-name'],
            "last_name" => $validatedData['last-name'],
            "email" => $validatedData['email'],
            "role" => $validatedData['role'] ?? 0,
            "is_site_operative" => $role->is_operative ?? 0,
            "joblead" => 0,
            "is_locked" => 0,
        ];

        $registerUser = User::create($data);

        // Check exist register
        if (is_null($registerUser)) {
            $response = CommonHelpers::failResponse(STATUS_FAIL,'Can not create new department. Please try again!');
        } else {
            $refUser = "ID" . $registerUser->id;
            $user = User::where('id', $registerUser->id )->update(['shine_reference' => $refUser]);
            // create user contact
            $dataContact = [
                'user_id' => $registerUser->id,
                'telephone' => $validatedData['telephone'],
                "mobile" => $validatedData['mobile']
            ];
            Contact::create($dataContact);

            // SEND REGISTER EMAIL
            $user = User::with('clients')->where('id', $registerUser->id)->first();
            $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
            $dataEmail = [
                'username' => $user->username,
                'domain' => \Config::get('app.url'),
                'subject' => 'Request User Password',
                'companyName' => $user->clients->name,
                'token' => $token,
                'type' => 2,
                'user_id' => $user->id
            ];

            //log audit
            // $comment =  "Guess register new user " . $user->full_name;
            // \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_ADD, $user->shine_reference, $user->client_id);
            $response = CommonHelpers::successResponse('Create new user successfully. Please check your email to finish',$dataEmail);
        }
        return $response;
    }

    public function createContractorUser($data) {

        try {
            // Create new department if pick other value
            $departmentID = end($data['departments']);
            $clientId = $data['client_id'];
            $client =  Client::find($clientId);
            if ($departmentID == -1) {
                $dataDepartment = [
                    'name' => $data['department-other'],
                    'client_id' => $clientId,
                ];
                if ($client->client_type == 0) {
                    $departmentNew = Department::create($dataDepartment);
                } else {
                    $departmentNew = DepartmentContractor::create($dataDepartment);
                }
                if (is_null($departmentNew)) {
                    return CommonHelpers::failResponse(STATUS_FAIL,'Can not create new department. Please try again!');
                }
                $departmentID = $departmentNew->id;
            }
            $username = trim($data['username']);
            $role = $this->jobRoleRepository->getDetail($data['role'] ?? 0, ['jobRoleViewValue']);
            $dataUser = [
                "username" => $username ,
                "password" => \Hash::make($data['password']) ,
                "client_id" => $clientId,
                "department_id" => $departmentID,
                "first_name" => $data['first_name'] ?? '',
                "last_name" => $data['last_name'] ?? '',
                "email" => $data['email'],
                "role" => $data['role'] ?? 0,
                "housing_officer" => (isset($data['housing_officer'])) ? 1 : 0,
                "is_site_operative" => $role->jobRoleViewValue->general_is_operative ?? 0,
                "joblead" => 0,
                "is_locked" => 0,
                "last_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'asbestos-awareness')),
                "last_shine_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'shine-asbestos')),
                "notes" => $data['notes'] ?? '',
            ];

            $user = User::create($dataUser);
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

            //save signature
            if (isset($data['signature'])) {
                $file = $data['signature'];
                if (!is_null($file) and $file->isValid()) {
                    try {
                        $path = \CommonHelpers::getFileStoragePath($user->id, USER_SIGNATURE);
                        Storage::disk('local')->put($path, $file);

                        ShineDocumentStorage::create([
                                'object_id' => $user->id,
                                'type' => USER_SIGNATURE,
                                'path' => $path. $file->hashName(),
                                'fileName' => $file->hashName(),
                                'mime' => $file->getClientMimeType(),
                                'size' => $file->getSize(),
                                'addedBy' => \Auth::user()->id,
                                'addedDate' =>  Carbon::now()->timestamp,
                            ]);
                    } catch (\Exception $e) {
                        return CommonHelpers::failResponse(STATUS_FAIL,'Failed to update signature. Please try again !');
                    }
                }
            }
            return CommonHelpers::successResponse('Create new contractor user successfully!', $user->id);
        } catch (\Exception $e) {
            CommonHelpers::failResponse(STATUS_FAIL,'Create User Fail. Please try again!');
        }

    }

    /**
     * Send reset password Email
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function resetPasswordEmail($data) {
        $email = $data['email'];
        $user = User::with('clients')->where('email', $email)->first();
        if ($user) {
            $token = app(\Illuminate\Auth\Passwords\PasswordBroker::class)->createToken($user);
            $data = [
                'username' => $user->getFullNameAttribute(),
                'domain' => \Config::get('app.url'),
                'subject' => 'Reset Password Email',
                'companyName' => $user->clients->name,
                'token' => $token,
                'type' => 1,
                'user_id' => $user->id
            ];

            //log audit
            // $comment = $user->full_name  . " reset password user " . $user->full_name;
            // \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
            return CommonHelpers::successResponse('Request forgot password successful. Please check your email to finish', $data);
        } else {
            return CommonHelpers::failResponse(STATUS_FAIL,'Request forgot password successful. Please check your email to finish');
        }

    }
    /**
     * Reset password from email
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function resetPassword($data) {
        $password = $data['password'];
        $user = User::with('resetPasswords')->where('id', $data['id'])->orderBy('created_at', 'desc')->first();

        if ( !$user ){
            return CommonHelpers::failResponse(STATUS_FAIL,'Token expired !');
        } else {
            if (!is_null($user->resetPasswords)) {
                if (\Hash::check($data['token'], $user->resetPasswords->token)) {
                    $user->password = \Hash::make($password);
                    $user->update();
                    //.logout user after change password
                    \Auth::logout();
                    // Delete the token
                    PasswordReset::where('email', $user->email)->delete();

                    //log audit
                    // $comment = \Auth::user()->full_name  . " reseted password of user " . $user->full_name;
                    // \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
                    return CommonHelpers::successResponse('Update password successful. Please login with new password');
                }
            }
            return CommonHelpers::failResponse(STATUS_FAIL,'Token invalid or expired!');
        }
    }

    public function changePassword($data, $id, $log_login = false) {
        $password = $data['password'];
        try {
            $userUpdate = User::where('id', $id)->update(['password' => \Hash::make($password)]);
            $user = User::find($id);
            //log audit
            $comment = \Auth::user()->full_name  . " edited password of " . $user->full_name;
            \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_ADD, $user->shine_reference, $user->client_id, $comment);
            if ($log_login) {
                $this->logLogin(true, $user->id, $user->username, $password);
            }
            return CommonHelpers::successResponse('Updated Password Successfully!');
        } catch (\Exception $e) {
            return CommonHelpers::failResponse(STATUS_FAIL,'Update password fail !');
        }
    }

    /**
     * Confirm and update new email
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function confirmEmail($data) {
        $password = $data['password'];
        $user = User::with('resetPasswords')->where('id', $data['id'])->first();

        if (\Hash::check($password, $user->password )){
            if (\Hash::check($data['token'], $user->resetPasswords->token)) {
                $user->email = $user->newEmail;
                $user->update();
                // Delete the token
                PasswordReset::where('token', $data['token'])->delete();
                return CommonHelpers::successResponse('Update new email successful');
            }else {
                return CommonHelpers::failResponse(STATUS_FAIL,'Token invalid !');
            }
        } else {
            return CommonHelpers::failResponse(STATUS_FAIL,'Password not match ! Please try again');
        }
    }

    /**
     * Get user from id
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function getUser($user_id) {
        return $user = User::with('clients','department')->where('id', $user_id)->first();
    }

    public function lockUser($id) {
        $user = User::where('id', $id)->first();
        try {
            if ($user->is_locked == USER_LOCKED) {
                User::where('id', $id)->update(['is_locked' => USER_UNLOCKED]);
                //log audit
                $comment = \Auth::user()->full_name  . " unlocked user " . $user->full_name;
                \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
                return \CommonHelpers::successResponse('Unlock User successful!');
            } else {
                User::where('id', $id)->update(['is_locked' => USER_LOCKED]);
                //log audit
                $comment = \Auth::user()->full_name  . " locked user " . $user->full_name;
                \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);
                return \CommonHelpers::successResponse('Lock User successful!');
            }
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Lock User Fail!');
        }
    }

    /**
     * Get user all department from user client
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function getAllUserDepartments($client_type, $clientID){
        if ($client_type == 0) {
            $departments = Department::with('allChildrens')->where('parent_id', 0)->get();
        } else {
            $departments = DepartmentContractor::with('allChildrens')->where('parent_id', 0)->orWhere('client_id', 0)->get();
        }
        $arr_temp[0]['selected'] = "";
        $arr_temp[0]['data'] = $departments;
        return CommonHelpers::successResponse('Get departments successful',$arr_temp);
    }

    public function getListSameLevelDepartment($department_id, $client_type, $clientID){
        $list_depart = [];
        if ($client_type == 0) {
            $departments = Department::find($department_id);
            if($departments){
                $arr_temp = Department::with('allParents')->where('parent_id', $departments->parent_id)->get();
                $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $client_type));
            }
        } else {
            $departments = DepartmentContractor::find($department_id);
            if($departments){
//                $list_depart = DepartmentContractor::with('allParents')->where('parent_id', $departments->parent_id)->get();

                $arr_temp = DepartmentContractor::with('allParents')->where('parent_id',$departments->parent_id)->get();
                $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $client_type));
            }
        }

        return CommonHelpers::successResponse('Get departments successful',$list_depart);
    }

    public function getListSameLevelDepartmentClient($department_id, $type, $clientID){
        $list_depart = [];
        if ($type != CONTRACTOR_TYPE) {
            $departments = Department::find($department_id);
            if($departments){
                $arr_temp = Department::with('allParents')->where('parent_id', $departments->parent_id)->get();
                $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $type));
            }
        } else {
            $departments = DepartmentContractor::find($department_id);
            if($departments){
//                $list_depart = DepartmentContractor::with('allParents')->where('parent_id', $departments->parent_id)->get();

                $arr_temp = DepartmentContractor::with('allParents')->where('parent_id',$departments->parent_id)->get();
                $list_depart = array_reverse($this->getParentDepartment($arr_temp, $list_depart, $department_id, $type));
            }
        }

        return CommonHelpers::successResponse('Get departments successful',$list_depart);
    }

    public function getParentDepartment($data, $list_depart, $selected, $type){
        $arr_temp = [];
        $arr_temp['selected'] = $selected;
        $arr_temp['data'] = $data;
        $list_depart[] = $arr_temp;
        if(isset($data[0]) && $data[0]){
            $parent_id = $data[0]->parent_id;
            if($type === CONTRACTOR_TYPE || $type == 1){
                $departments = DepartmentContractor::find($parent_id);
            } else {
                $departments = Department::find($parent_id);
            }
            if($departments){
                if($type === CONTRACTOR_TYPE || $type == 1){
                    $arr_temp = DepartmentContractor::where('parent_id', $departments->parent_id)->get();
                } else {
                    $arr_temp = Department::where('parent_id', $departments->parent_id)->get();
                }
                return $this->getParentDepartment($arr_temp, $list_depart, $parent_id, $type);
            }
        }
        return $list_depart;
    }

    /**
     * Update user
     * Send email confirm after success
     * @param  [type] $validatedData [description]
     * @return [type]                [description]
     */
    public function updateUser($id, $data) {

        $user = User::with('clients', 'contact', 'department')->where('id',$id)->first();
        $userOldEmail = $user->email ?? '';
        $changeEmail = false;

        // convert datepicker to d/m/y format
        // $last_asbestos_training = Carbon::createFromFormat('d/m/Y', $data['asbestos-awareness']);
        // $last_shine_asbestos_training = Carbon::createFromFormat('d/m/Y', $data['shine-asbestos']);
        $role = Role::find($data['role'] ?? 0);
        $dataUserUpdate = [
            "username" => $data['username'] ,
            "department_id" => end($data['departments']),
            "first_name" => $data['first-name'],
            "last_name" => $data['last-name'],
            "notes" => $data['notes'],
            "role" => $data['role'],
            "is_admin" => (isset($data['is-admin'])) ? 1 : 0,
            "is_site_operative" => $role->is_operative ?? 0,
            // convert to timestamp
            "last_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'asbestos-awareness')),
            "last_shine_asbestos_training" => \CommonHelpers::toTimeStamp(\CommonHelpers::checkArrayKey($data, 'shine-asbestos')),
        ];

        if ($userOldEmail !== $data['email']) {
            $dataUserUpdate['newEmail'] = $data['email'];
            $changeEmail = true;
        }

        $dataContactUpdate = [
            'telephone' => $data['telephone'],
            'mobile' => $data['mobile'],
            'job_title' => $data['job-title'],
        ];
        //save file
        if (isset($data['signature'])) {
            $file = $data['signature'];
            if (!is_null($file) and $file->isValid()) {
                try {
                    $path = \CommonHelpers::getFileStoragePath($id, USER_SIGNATURE);
                    Storage::disk('local')->put($path, $file);

                    ShineDocumentStorage::updateOrCreate([ 'object_id' => $id, 'type' => USER_SIGNATURE,],
                        [
                            'path' => $path. $file->hashName(),
                            'fileName' => $file->hashName(),
                            'mime' => $file->getClientMimeType(),
                            'size' => $file->getSize(),
                            'addedBy' => \Auth::user()->id,
                            'addedDate' =>  Carbon::now()->timestamp,
                        ]);
                } catch (\Exception $e) {
                    return CommonHelpers::failResponse(STATUS_FAIL,'Failed to update signature. Please try again !');
                }
            }
        }


        //update data
        $userUpdate = $user->update($dataUserUpdate);
        $contactUpdate = Contact::where('user_id', $id)->update($dataContactUpdate);
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
            \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id, $comment);

            //queue send email
            \Queue::pushOn(EMAIL_RESET_PASSWORD_QUEUE,new SendEmail($userOldEmail, $dataEmail,EMAIL_CHANGE_MAIL_QUEUE));

            return CommonHelpers::successResponse('Update user successfully, your new email is ready to change. Please check email to finish!');
        }
        //log audit
        $comment = \Auth::user()->full_name  . " edited user " . $user->full_name;
        \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_EDIT, $user->shine_reference, $user->client_id, $comment);
        return CommonHelpers::successResponse('Update user successfully!');
    }

    /**
     * get list of user when editting/adding property
     * @param int $client_id
     * @return Users
     */
    public static function getListUsersFromOrg($client_id){
        $user = [];
        if (is_numeric($client_id)) {
          $user = User::where(['client_id'=> $client_id, 'is_locked' => Client::$USER_UNLOCKED])->get();
        }
        return $user;
    }

    public function getListAsbestosLeadWorkRequest(){
        return User::where('is_lead_workrequest', LEAD_WORKREQUEST)->orderBy('first_name')->get();
    }

    public function searchUser($query_string) {
        if (!\CommonHelpers::isSystemClient()) {
            $condition = 'AND client_id = '. \Auth::user()->client_id;
        } else {
            $condition = '';
        }

        $sql = "SELECT id,username,shine_reference,first_name, last_name,CONCAT(`first_name`, ' ',`last_name`) as full_name
                FROM `tbl_users`
                WHERE (`shine_reference` LIKE '%" . $query_string . "%'
                    OR `username` LIKE '%" . $query_string . "%'
                    OR `first_name` LIKE '%" . $query_string . "%'
                    OR `last_name` LIKE '%" . $query_string . "%'
                    OR CONCAT(`first_name`, ' ',`last_name`) LIKE '%" . $query_string . "%' )
                    $condition
                ORDER BY `username` ASC
                LIMIT 0,30";

        $list = DB::select($sql);
        return $list;
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

        LogLogin::create($dataLog);
    }

    public function getDataTrainingRecord($user){
        $data = [];
        if($user){
            $next_course_date_ukata = $user->last_asbestos_training && $user->last_asbestos_training > 0 ? $user->last_asbestos_training + 365 * 86400 : 0;
            $next_course_date_sot = $user->last_site_operative_training && $user->last_site_operative_training > 0 ? $user->last_site_operative_training + 365 * 86400 : 0;
            $next_course_date_pj = $user->last_project_training && $user->last_project_training > 0 ? $user->last_project_training + 365 * 86400 : 0;
            $data[0] = [
                'name' => 'UKATA Asbestos Awareness Training',
                'previous_course_date' => $user->last_asbestos_training,
                'next_course_date' => $next_course_date_ukata,
                'days_remaining' => CommonHelpers::getDaysRemaninng($next_course_date_ukata)
            ];
            $data[1] = [
                'name' => 'Site Operative View Training',
                'previous_course_date' => $user->last_site_operative_training,
                'next_course_date' => $next_course_date_sot,
                'days_remaining' => CommonHelpers::getDaysRemaninng($next_course_date_sot)
            ];
            $data[2] = [
                'name' => 'Project Manager Training',
                'previous_course_date' => $user->last_project_training,
                'next_course_date' => $next_course_date_pj,
                'days_remaining' => CommonHelpers::getDaysRemaninng($next_course_date_pj)
            ];

        }
        return $data;
    }

}
