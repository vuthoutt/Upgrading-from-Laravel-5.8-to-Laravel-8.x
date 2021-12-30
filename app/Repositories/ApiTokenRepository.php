<?php
namespace App\Repositories;
use App\Models\ApiToken;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CommonHelpers;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Str;
use App\Models\Decommission;
use App\Models\DecommissionReason;
use App\Models\PropertyProgrammeType;
use App\Models\PropertyDropdown;
use Carbon\Carbon;

class ApiTokenRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ApiToken::class;
    }

    public function login($email, $password)
    {

        $user = User::where('email', $email)->first();

        if (!is_null($user)) {
            if(\Hash::check($password, $user->password)){
                if ($user->is_locked == -1 || $user->is_locked == 1) {
                    // if user is locked
                    $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Your account is locked. Please contact the Shine Team.');
                } else {
                    // login success
                    $response = CommonHelpers::successResponse('Login successfully',$user);
                }
            } else {
                $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'Unauthorized');
            }
        } else {
            $response = CommonHelpers::failResponse(STATUS_FAIL_CLIENT,'User not exist');
        }
        return $response;
    }

    public function checkToken($user)
    {   $result = ['id'=>null,'token'=>null,'expired_at'=>null];
        $api_token = $user->apiToken;
        if(!isset($api_token->is_token_expired)){
            $result['token'] = uniqid(base64_encode(Str::random(60)));
            $result['expired_at'] = Carbon::parse(Carbon::now())->addDays(7);
        }else{
            $result['id'] = $api_token->id;
            $result['token'] = $api_token->token;
            $result['expired_at'] = $api_token->expired_at;
        }
        return $result;
    }

    /**
     * logout set expired date of token = now
     */
    public function invalidate($token){
        $expired_now = date_format(Carbon::now(), 'Y-m-d H:i:s');
        $api_token = ApiToken::where('token', $token)->update(['expired_at' => $expired_now]);
    }

    /**
     * get all parent dropdown for old API
     */
    public function getAllParentDropdown(){
        $result = DB::select('SELECT * FROM tbldropdown');
        return $result;
    }
    /**
     * get all child dropdown for old API
     */
    public function getAllChildDropdown(){
        $result = DB::select('SELECT * FROM tbldropdowndata where decommissioned = 0');
        return $result;
    }

    public function getAllDecommission() {
        $decommissions =  Decommission::all();
        $data = [];
        foreach ($decommissions as $decommission) {
            $data[] = [
                "ID" => $decommission->id,
                "description" => $decommission->description
            ];
        }

        return $data;
    }

    public function getAllDecommissionReason() {
        $decommissionReasons =  DecommissionReason::all();
        $data = [];
        foreach ($decommissionReasons as $decommissionReason) {
            $data[] = [
                "ID" => $decommissionReason->id,
                "description" => $decommissionReason->description,
                "type" => $decommissionReason->type,
                "parentID" => $decommissionReason->parent_id
            ];
        }

        return $data;
    }

    public function propertyAccessType() {
        $propertyAccessTypes =  PropertyProgrammeType::all();
        $data = [];
        foreach ($propertyAccessTypes as $propertyAccessType) {
            $data[] = [
                "ID" => $propertyAccessType->id,
                "description" => $propertyAccessType->description,
            ];
        }

        return $data;
    }

    public function propertyDropdownData() {
        $propertyDropdownDatas =  PropertyDropdown::all();
        $data = [];
        foreach ($propertyDropdownDatas as $propertyDropdownData) {
            $data[] = [
                "ID" => $propertyDropdownData->id,
                "dropdownID" => $propertyDropdownData->dropdown_id,
                "description" => $propertyDropdownData->description
            ];
        }

        return $data;
    }
}
