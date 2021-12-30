<?php

namespace App\Http\Request\API;


class ApiUploadBackupRequest extends APIRequest
{
    public function rules()
    {
        return [
            'userID' => 'required|integer',
            'surveyID' => 'required|integer',
            'zip' => 'required|file',
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
            'zip.required' => 'The zip file is required!',
        ];
    }


}
