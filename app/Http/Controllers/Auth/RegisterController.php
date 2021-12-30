<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\UserRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DepartmentRepository;
use App\Http\Request\User\UserCreateRequest;
use App\Jobs\SendEmail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    private $userRepository;
    private $clientsRepository;
    private $departmentsRepository;
    private $userCreateRequest;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo,ClientRepository $ClientRepository,DepartmentRepository $DepartmentRepository)
    {
        $this->userRepository = $userRepo;
        $this->clientRepository = $ClientRepository;
        $this->departmentRepository = $DepartmentRepository;

    }

    /**
     * show register form
    * @return
     */
    public function index() {
        $roleSelect = Role::all();
        $roleSelect = json_decode(json_encode($roleSelect));
        $clients = $this->clientRepository->with(['departments'])->all();
        return view('auth.register',['clients' => $clients, 'roleSelect' => $roleSelect]);
    }

    /**
     * get organisation department by client id
    * @return
     */
    public function getClientDepartments($organisation) {
        $departments = $this->departmentRepository->getClientDepartments($organisation);
        return $departments;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function register(UserCreateRequest $userCreateRequest)
    {
        $validatedData = $userCreateRequest->validated();

        $userRegister = $this->userRepository->register($validatedData);
        $emailData = $userRegister['data'];

        if (isset($userRegister)) {
            if ($userRegister['status_code'] == 200) {
                // queue send email
                \Queue::pushOn(EMAIL_REGISTER_QUEUE,new SendEmail($validatedData['email'], $emailData,EMAIL_REGISTER_QUEUE));
                return redirect('login')->with('msg', $userRegister['msg']);
            } else {
                return redirect()->back()->with('err', $userRegister['msg']);
            }
        }
    }

}
