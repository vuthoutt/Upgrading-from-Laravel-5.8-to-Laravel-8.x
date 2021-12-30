<?php

namespace App\Http\Request\API;


class ApiUploadBackupDataRequestV2 extends APIRequest
{
    public function rules()
    {
        return [
            'userID' => 'required|integer',
            'surveyID' => 'required|integer',
            'imageCount' => 'required|integer',
            'file' => 'required|file',
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
            'file.required' => 'The zip file is required!',
        ];
    }


}
