<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\AppBaseController;
use app\Http\Controllers\Migration\MigrationUploadDataController;
use App\Http\Request\API\GetManifestRequest;
use App\Http\Request\API\GetSurveyDetailRequest;
use App\Http\Request\API\RegisterUserRequest;
use App\Http\Request\API\UserLoginRequest;
use App\Models\ApiToken;
use App\Models\DownloadManifest;
use App\Models\ShineAppDocumentStorage;
use App\Models\UploadManifest;
use App\Repositories\ApiTokenRepository;
use App\Repositories\DownloadManifestRepository;
use App\Repositories\SurveyRepository;
use App\Repositories\UploadManifestRepository;
use App\Repositories\UserRepository;
use App\Jobs\SendEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Created by PhpStorm.
 * User: Hoang Tran
 * Date: 8/8/2019
 * Time: 3:55 PM
 */
class APIController extends  AppBaseController
{
    private $apiTokenRepository;
    private $downloadManifestRepository;
    private $surveyRepository;
    private $userRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository,
        SurveyRepository $surveyRepository,
        DownloadManifestRepository $downloadManifestRepository,
        UploadManifestRepository $uploadManifestRepository,
        UserRepository $userRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->downloadManifestRepository = $downloadManifestRepository;
        $this->surveyRepository = $surveyRepository;
        $this->uploadManifestRepository = $uploadManifestRepository;
        $this->userRepository = $userRepository;
    }

    // login need return token + all survey details
    public function login(UserLoginRequest $request)
    {

        // $credentials = $request->only('username', 'password');
        $response = $this->apiTokenRepository->login($request->username, $request->password);
        if ($response['status_code'] != Response::HTTP_OK) {
            return $this->sendError($response['msg'], $response['status_code']);
        }
        $user = $response['data'];
        //if check token is expired then create new token else create new token
        $result = $this->apiTokenRepository->checkToken($user);
        ApiToken::updateOrCreate(['user_id' => $user->id], ['user_id' => $user->id, 'token' => $result['token'], 'expired_at' => $result['expired_at']]);
        return $this->sendResponse(['token' => $result['token'], 'user_id' => $user->id], 'successfully');
    }

    // register new user
    public function register(RegisterUserRequest $request)
    {
        $data = $request->all();
        // create new user
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        //create new api token
        $new_token = uniqid(base64_encode(Str::random(60)));
        $expired_at = date_format(Carbon::now()->addDays(7), 'Y-m-d H:i:s');
        ApiToken::updateOrCreate(['user_id' => $user->id], ['user_id' => $user->id, 'token' => $new_token, 'expired_at' => $expired_at]);
        return response()->json(compact('user', 'token'), 201);
    }

    // refresh token that is expired

    public function logout(Request $request)
    {
        $this->apiTokenRepository->invalidate($request->bearerToken());
        return $this->sendResponse('', 'successfully');
    }


    public function getManifest(GetManifestRequest $request)
    {
        $user_id = $request->user_id;

        $list_survey = $this->surveyRepository->getDataManifest($user_id);

        if (count($list_survey)) {
            $insert = [];
            $now = Carbon::now();//same time

            $list_survey_id = implode(",", $list_survey->keys()->all());
            $insert[] = ['user_id' => $user_id,
            'list_survey_id' => $list_survey_id,
            'created_at' => $now,
            'updated_at' => $now];
            // insert into download manifest table
            DownloadManifest::insert($insert);
        }
        return $this->sendResponse($list_survey, 'successfully');
    }

    public function resetPassword(Request $request) {
        if ($request->has('email')) {
            $data['email'] = $request->email;
            $user = User::with('clients')->where('email', $data['email'])->first();
            if (is_null($user)) {
                return response()->json(['success'=> false]);
            } else {
                $getEmailData = $this->userRepository->resetPasswordEmail($data);
                $emailData = $getEmailData['data'];
                \Queue::pushOn(EMAIL_RESET_PASSWORD_QUEUE,new SendEmail($data['email'], $emailData, EMAIL_RESET_PASSWORD_QUEUE));
                return response()->json(['success'=> true]);
            }

        } else {
            return response()->json(['success'=> false, 'message' => 'The email field is required!']);
        }
    }
}
