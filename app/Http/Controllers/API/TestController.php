<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use App\User;

/**
 * Created by PhpStorm.
 * User: Hoang Tran
 * Date: 8/8/2019
 * Time: 3:55 PM
 */
class TestController extends  Controller{
    /**
     * @var JWTAuth
     */
    private $jwtAuth;
    // independency
    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
//        $this->middleware('jwt.auth', ['except' => ['login']]);
//        dd($this->jwtAuth);
    }
    //login for user base on
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = $this->jwtAuth->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(['token'=>$token]);
    }
    // register new user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tbl_users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = $this->jwtAuth->fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    // refresh token that is expired
    public function refresh()
    {
        $token = $this->jwtAuth->getToken();
        $token = $this->jwtAuth->refresh($token);
        return response()->json(compact('token'));
    }
    // logout user
    public function logout()
    {
        $token = $this->jwtAuth->getToken();
        $this->jwtAuth->invalidate($token);
        return response()->json(['logout']);
    }
    // get user info base on token
    public function getUserInfo(Request $request){
//        dd($request);
        try {

            $user = $this->jwtAuth->toUser($request->token);
        }catch (JWTException $e){
//            dd($e);
            if ($e instanceof TokenBlacklistedException ) {
                return response()->json(['token_blacklisted']);// token is invalid
            }
        }
//        dd($user);
        return response()->json(['result' => $user]);
    }
}
