<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\User;
use App\Models\Elearning;
use App\Models\ElearningApi;
use App\Models\ElearningLog;

class ElearningController extends  Controller{

    private $client;
    private $user;
    public static $hash_key = '9v5x9YkgDnmkjIYIEXD4IChPeyKfCoop';
    public static $url_enroll = 'https://litmos-api.santia.co.uk/api/enrol';
    public static $url_begin = 'https://litmos-api.santia.co.uk/api/authenticate';
    public static $url_update = 'https://litmos-api.santia.co.uk/api/update';
    public static $type_enroll = 1;
    public static $type_begin = 2;
    public static $type_complete = 3;

    public static function init_curl_elearning($url, $datas){
        if(count($datas)){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                http_build_query($datas));
            $output = curl_exec($ch);
            curl_close ($ch);
            return $output;
        }
    }

    /**
     * Get the response
     * @return string
     * @throws \RuntimeException On cURL error
     */
    public function getResponse()
    {
        if ($this->response) {
            return $this->response;
        }

        $response = curl_exec($this->ch);
        $error    = curl_error($this->ch);
        $errno    = curl_errno($this->ch);

        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        return $this->response = $response;
    }

    public function enroll($user_id) {
        $user = User::find($user_id);

        if (!is_null($user)) {
            $type = self::$type_enroll;
            $param = [
                'emailAddress' => $user->email,
                'firstName' => $user->first_name,
                'lastName'=> $user->last_name
                ];

            $paramJSON = json_encode($param);
            $signed = crypt($paramJSON, self::$hash_key);

            $postData = [
                'signed' => $signed,
                'emailAddress' => $user->email,
                'firstName' => $user->first_name,
                'lastName' => $user->last_name
                ];
            try {
                $response = self::init_curl_elearning(self::$url_enroll, $postData);
                $response = json_decode($response);
                if (is_numeric($response->statusCode)) {
                    if ($response->statusCode == 201) {
                       Elearning::create([
                            'user_id' => $user->id,
                            'token_id' =>$response->TokenID,
                            'login_key' =>$response->LoginKey,
                       ]);
                       $this->addLog($user->id, $response, "Authentication Successful", $type);
                        //log audit
                        $comment = \Auth::user()->full_name  . " enrolled on the Asbestos Awareness Course ";
                        \CommonHelpers::logAudit(ELEARNING_TYPE, \Auth::user()->id, AUDIT_ACTION_ENROLL, \Auth::user()->full_name, \Auth::user()->client_id ,$comment);
                        return [
                                "status_code" => STATUS_OK,
                                "msg" => "Enroll sucessfully"
                            ];
                    } else {
                        $this->addLog($user->id, $response, "Enrollment is only allowed once", $type);
                        return [
                                "status_code" => STATUS_FAIL_CLIENT,
                                "msg" => 'Enrollment is only allowed once'
                            ];
                    }
                 }

                } catch (Exception $e) {
                    return [
                        "status_code" => STATUS_FAIL_CLIENT,
                        "msg" => $e->getMessage()
                    ];
                }
        } else {
            return [
                'status_code' => STATUS_FAIL_CLIENT,
                'msg' => 'User does not exist',
            ];
        }
    }

    public function authenticateUser($user_id) {
        $user = User::find($user_id);
        if (!is_null($user)) {
                $type = self::$type_begin;

                $elearning_obj = Elearning::where('user_id', $user->id)->first();
                $tokenID = $elearning_obj->token_id ?? '';
                $epochDate = time();
                $someArray = ["epochDate"=>$epochDate,
                    'tokenID'=>$tokenID];
                $someJSON = json_encode($someArray);

                $signed = crypt($someJSON, self::$hash_key);

                if ($tokenID) {
                    $postData = [
                        'signed' => $signed,
                        'epochDate' => $epochDate,
                        'tokenID' => $tokenID,
                    ];
                try {
                    $response = self::init_curl_elearning(self::$url_begin, $postData);
                    $response = json_decode($response);

                    if (is_numeric($response->statusCode)) {
                        if ($response->statusCode == 200) {
                           $this->addLog($user->id, $response, "Authentication Successful", $type);
                            //log audit
                            $comment = \Auth::user()->full_name  . " began the Asbestos Awareness Course ";
                            \CommonHelpers::logAudit(ELEARNING_TYPE, \Auth::user()->id, AUDIT_ACTION_BEGIN, \Auth::user()->full_name, \Auth::user()->client_id ,$comment);
                            return [
                                    "status_code" => STATUS_OK,
                                    "url" => $response->LoginKey
                                ];
                        } else {
                            return [
                                    "status_code" => $response->statusCode,
                                    "msg" => $response->message
                                ];
                        }
                    }

                    } catch (Exception $e) {
                        return [
                            "status_code" => STATUS_FAIL_CLIENT,
                            "msg" => $e->getMessage()
                        ];
                    }
                }
                $this->addLog($user->id, null, "Not found token id when user click begin", $type);
                return [
                    "status_code" => STATUS_FAIL_CLIENT,
                    "msg" => 'Have something wrong , please try again!'
                ];
        } else {
                return [
                    'status_code' => STATUS_FAIL_CLIENT,
                    'msg' => 'Please enroll in a course before beginning',
                ];
            }
    }

    public function addLog($user_id, $response, $exception_message, $type) {
        $message = isset($response->message) ? $response->message :  '';
        $tokenID = isset($response->TokenID) ? $response->TokenID :  '';
        $statusCode = $response->statusCode ??  '';
        $loginKey = isset($response->LoginKey) ? $response->LoginKey :  '';

        ElearningLog::create([
            "user_id" => $user_id,
            "message" => $message,
            "exception_message" => $exception_message,
            "status_code" => $statusCode,
            "ip_address" => \Request::ip(),
            "type" => $type,
        ]);
    }

    //for Site Operative View Training Video
    public static function completeTraningSiteOperativeView(){
        $user_id = \Auth::user()->id;
        $cur_time = time();
        User::where('id', $user_id)->update(['last_site_operative_training' => $cur_time]);
        return [
            "error" => FALSE,
            "message" => "completed the course"
        ];
    }
    //for Project Manager Training Video
    public static function completeTraningProjectManager(){
        $user_id = \Auth::user()->id;
        $cur_time = time();
        User::where('id', $user_id)->update(['last_project_training' => $cur_time]);
        return [
            "error" => FALSE,
            "message" => "completed the course"
        ];
    }

    public static function recieveData(Request $request) {
        $data = $request->all();
        $message_log = "No data";
        $user_id = NULL;
        $type_log = self::$type_complete;
        if (count($data)) {
            $message_log = "Installed data successfully";
            ElearningApi::create(['data' => json_encode($data)]);
            //update date complete course to user
            if (isset($data['epochDate']) && is_numeric($data['epochDate']) && isset($data['tokenID'])) {
                try {
                    //get tokenID and user ID to check
                    $user = Elearning::where('token_id', $data['tokenID'])->first();


                    if (!is_null($user)) {
                        $user_id =  $user->id;
                        User::where('id',$user_id)->update(['last_asbestos_training' => $data['epochDate']]);
                        $message_log = "Installed data successfully, updated date complete for userID: " . $user_id;
                        self::addLog($user_id, NULL, $message_log, $type_log);
                        $currentUser = User::find($user_id);
                        \CommonHelpers::logAudit(ELEARNING_TYPE, $user_id, AUDIT_ACTION_COMPLETE, 'Asbestos Awareness Course', 0 , $currentUser->full_name . ' completed the Asbestos Awareness Course');
                    }

                } catch (Exception $ex) {
                    if (isset($user_id)) {
                        $message_log = $ex->getMessage();
                        self::addLog('', NULL, $message_log, $type_log);
                    }
                }
            }
        }

        return json_encode(['status ' => 200, 'data' => 'Installed successfully']);
    }
}
