<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Request\User\UserLoginRequest;
use Illuminate\Http\Response;
use App\Repositories\UserRepository;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/zone/zone-map?client_id=1';
    private $userRepository;
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
     * show login form
    * @return
     */
    public function index() {

        return view()->first([
                'GSK::login',
                'CSAT::login',
            'auth.login']);
    }

    /**
     * login action
    * @return
     */
    public function login(UserLoginRequest $userLoginRequest) {
        $userLoginRequest->session()->get('property_session', []);
        $validatedData = $userLoginRequest->validated();
        $remember = $userLoginRequest->has('remember') ? true :false;
        if ($remember) {
            /* Set cookie to last 1 year */
            \Cookie::queue('username', $_POST['username'], time() + 60 * 60 * 24 * 365);
            \Cookie::queue('password', $_POST['password'], time() + 60 * 60 * 24 * 365);
            \Cookie::queue('remember', $_POST['remember'], time() + 60 * 60 * 24 * 365);
        } else {
            /* Cookie expires when browser closes */
            \Cookie::queue('username', "", 0);
            \Cookie::queue('password', "", 0);
            \Cookie::queue('remember', "", 0);
        }
        $user = $this->userRepository->login($validatedData);
        if (isset($user) and !is_null($user)) {
            // login success
            if ($user['status_code'] == 200) {
                return $this->authenticated($userLoginRequest, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
            // first login
            } else if($user['status_code'] == 401) {
                 return redirect()->route('user.first_change_password', ['id' => \Auth::user()->id])->with('msg', $user['msg']);
            } else {
                return redirect()->back()->with('err', $user['msg']);
            }
        }
    }

    /**
     * logout action
    * @return
     */
    public function logout() {
        session()->forget('property_session');
        \Auth::logout();
        return redirect('login');
    }

    /**
     * change default form from email to username
    * @return
     */
    public function username()
    {
        return 'username';
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
        if(env('SETTING_PROPERTY_MAP') == true) {
            if (\Auth::user()->role == 1) {
                return '/home';
            } else {
                return '/zone/zone-map?client_id=1';
            }
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
