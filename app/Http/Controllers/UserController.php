<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentContractor;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Http\Request\User\UserUpdateRequest;
use App\Http\Request\User\ChangePasswordRequest;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Show user profile by id.
     *
     */
    public function profile($user_id)
    {
        $user = $this->userRepository->getUser($user_id);
        $data_trainings = $this->userRepository->getDataTrainingRecord($user);
        //log audit
        $comment = \Auth::user()->full_name  . " view user " . $user->full_name;
        \CommonHelpers::logAudit(USER_TYPE, $user->id, AUDIT_ACTION_VIEW, $user->shine_reference, $user->client_id);
        return view('user.profile',['userData' => $user, 'data_trainings' => $data_trainings]);
    }

    /**
     * show edit user form
     * @return
     */
    public function getEdit($user_id) {
        $user = $this->userRepository->getUser($user_id);
        if (is_null($user)) {
            abort(404);
        }
        $roleSelect = Role::all();
        $roleSelect = json_decode(json_encode($roleSelect));
        if($user->clients->client_type == 0){
            $departments = Department::find($user->department_id);
        } else {
            $departments = DepartmentContractor::find($user->department_id);
        }
        if(!$user->department_id || !$departments){
            $departments = $this->userRepository->getAllUserDepartments($user->clients->client_type, $user->client_id);
        } else {
            $departments = $this->userRepository->getListSameLevelDepartment($user->department_id, $user->clients->client_type, $user->client_id);;
        }

        return view('user.edit',['userData' => $user,'departments' => $departments['data'], 'roleSelect' => $roleSelect]);
    }
    /**
     * edit user
     * @return
     */
    public function postEdit(UserUpdateRequest $userUpdateRequest,$id) {
        $validatedData = $userUpdateRequest->validated();
        $userUpdate = $this->userRepository->updateUser($id, $validatedData);

        if (isset($userUpdate) and !is_null($userUpdate)) {
            if ($userUpdate['status_code'] == 200) {
                return redirect()->route('profile', ['user' => $id])->with('msg', $userUpdate['msg']);
            } else {
                return redirect()->back()->with('err', $userUpdate['msg']);
            }
        }
    }

    /**
     * show confrim new email change form
     * @return
     */
    public function getConfirmNewEmail($slashData) {
        $id = \Request('id');
        $token = $slashData;
        return view('user.confirm_email',['token' => $token ,'id' => $id]);
    }

    /**
     * confirm new email change
     * @return
     */
    public function postConfirmNewEmail() {
        $data = \Request::all();
        $confirmEmail = $this->userRepository->confirmEmail($data);

        if (isset($confirmEmail) and !is_null($confirmEmail)) {
            if ($confirmEmail['status_code'] == 200) {
                return redirect()->route('profile', ['user' => $data['id']])->with('msg', $confirmEmail['msg']);
            } else {
                return redirect()->back()->with('err', $confirmEmail['msg']);
            }
        }
    }

    public function getChangePassword($id) {
        $user = $this->userRepository->getUser($id);
        if (is_null($user)) {
            abort(404);
        }
        return view('user.change_password',['userData' => $user]);
    }

    public function firstResetPassword($id) {
        $user = $this->userRepository->getUser($id);
        if (is_null($user)) {
            abort(404);
        }
        return view('user.first_reset_password',['userData' => $user]);
    }

    public function postChangePassword($id,ChangePasswordRequest $changePasswordRequest) {
        $validatedData = $changePasswordRequest->validated();

        $first_login = isset($validatedData['first_login']) ? true : false;
        $updatePassword = $this->userRepository->changePassword($validatedData, $id, $first_login);
        if (isset($updatePassword) and !is_null($updatePassword)) {
            if ($updatePassword['status_code'] == 200) {
                return redirect()->route('profile', ['user' => $id])->with('msg', $updatePassword['msg']);
            } else {
                return redirect()->back()->with('err', $updatePassword['msg']);
            }
        }
    }

    public function lock($id) {
        $lockUser = $this->userRepository->lockUser($id);

        if (isset($lockUser) and !is_null($lockUser)) {
            if ($lockUser['status_code'] == 200) {
                return redirect()->back()->with('msg', $lockUser['msg']);
            } else {
                return redirect()->back()->with('err', $lockUser['msg']);
            }
        }
    }

    public function searchUser(Request $request) {
        $query_string = '';
        if ($request->has('query_string')) {
            $query_string = $request->query_string;
        }
        $data = $this->userRepository->searchUser($query_string);
        return response()->json($data);
    }
}
