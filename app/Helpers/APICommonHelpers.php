<?php
namespace App\Helpers;
class APICommonHelpers {
    /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    public static function makeResponse($message, $data = null)
    {
        $res = [
            'success' => true,
            'status_code' => 200,
            'message' => $message,

        ];
        is_null($data) ? '' : $res['data'] = $data;
        return $res;
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    public static function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }

    /**
     * get token from header
     */
    public static function getToken($header_authorization){
        $token = $header_authorization;
    }
}
