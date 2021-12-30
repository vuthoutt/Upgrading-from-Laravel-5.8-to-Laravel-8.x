<?php

namespace App\Http\Request\API;


class ApiUploadBackupImageRequestV2 extends APIRequest
{
    public function rules()
    {
        return [
            'backupID' => 'required|integer',
            'appID' => 'required|integer',
            'type' => 'required',
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
            'backupID.required' => 'The backupID field is required!',
            'appID.required' => 'The appID field is required!',
            'type.required' => 'The type field is required!',
            'file.required' => 'The file data is required!',
        ];
    }


}
