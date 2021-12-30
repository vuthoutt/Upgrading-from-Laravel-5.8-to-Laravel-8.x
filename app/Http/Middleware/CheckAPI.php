<?php

namespace App\Http\Middleware;
use App\Models\ApiToken;
use App\Repositories\ApiTokenRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Closure;
use App\Helpers\APICommonHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response AS ResponseStatus;

class CheckAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd($api_token, 123);
        //$request->header('Authorization')
        //$request->bearerToken()
        try {
            $token = $request->bearerToken();
            if(!isset($token)){
                //get token from header
                //header application:json
                //Authorization : Bearer $token
//                return response()->json(['error'=>'Token is required', 'status'=> ResponseStatus::HTTP_FOUND]);
                return Response::json(APICommonHelpers::makeError('Token is required' ),ResponseStatus::HTTP_FOUND);
            }
            $api_token = ApiToken::where(['token'=> $token])->first();
            if(!$api_token){
//                return response()->json(['error'=>'Token is invalid', 'status'=> ResponseStatus::HTTP_FOUND]);
                return Response::json(APICommonHelpers::makeError('Token is invalid'), ResponseStatus::HTTP_FOUND);
            } else {
                Auth::setUser($api_token->user);
//                if($api_token->is_token_expired){
////                    return response()->json(['error'=>'Token is expired', 'status'=> ResponseStatus::HTTP_FOUND]);
//                    return Response::json(APICommonHelpers::makeError('Token is expired'), ResponseStatus::HTTP_FOUND);
//                }else{
                    //update expired for token
                    $expired_at = date_format(Carbon::now()->addYears(1), 'Y-m-d H:i:s');
                    $api_token->updated(['expired_at'=>$expired_at]);
//                }
            }
        }catch (\Exception $e) {
            //update this message later
//            return response()->json(['error'=>$e->getMessage(),  'status'=> ResponseStatus::HTTP_FOUND]);
            return Response::json(APICommonHelpers::makeError($e->getMessage()), ResponseStatus::HTTP_FOUND);
        }
        return $next($request);
    }
}
