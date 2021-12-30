<?php

namespace App\Http\Request\API;


class ApiListBackupRequest extends APIRequest
{
    public function rules()
    {
        return [
            'userID' => 'required|integer',
            'surveyID' => 'required|integer',
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'userID.required' => 'The userID field is required!',
            'surveyID.required' => 'The surveyID field is required!',
        ];
    }


}
