<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Mail\ResetPasswordEmail;
use App\Jobs\SendEmail;
use App\Http\Request\User\ResetPasswordRequest;
use App\Http\Request\User\NewPasswordRequest;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    public function showResetForm($slashData) {
        $id = \Request('id');
        $token = $slashData;
        return view('auth.passwords.reset',['token' => $token ,'id' => $id]);
    }

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
     * show reset password form
     * send reset link email after reset
     * @return
     */
    public function sendResetLinkEmail(ResetPasswordRequest $resetPasswordRequest) {
        $validatedData = $resetPasswordRequest->validated();
        $getEmailData =  $this->userRepository->resetPasswordEmail($validatedData);
        if (isset($getEmailData)) {
            $emailData = $getEmailData['data'];
            if ($getEmailData['status_code'] == 200) {
                //queue send email
                \Queue::pushOn(EMAIL_RESET_PASSWORD_QUEUE,new SendEmail($validatedData['email'], $emailData, EMAIL_RESET_PASSWORD_QUEUE));

                return redirect('login')->with('msg', $getEmailData['msg']);
            } else {
                return redirect()->back()->with('err', $getEmailData['msg']);
            }
        }
    }

    /**
     * reset password
    * @return
     */
    public function reset(NewPasswordRequest $newPasswordRequest) {
        $validatedData = $newPasswordRequest->validated();
        $resetPassword = $this->userRepository->resetPassword($validatedData);

        if (isset($resetPassword) and !is_null($resetPassword)) {
            if ($resetPassword['status_code'] == 200) {
                return redirect('login')->with('msg', $resetPassword['msg']);
            } else {
//                return redirect()->back()->with('err', $resetPassword['msg']);
                return redirect('login')->with('msg', $resetPassword['msg']);
            }
        }
    }

}
