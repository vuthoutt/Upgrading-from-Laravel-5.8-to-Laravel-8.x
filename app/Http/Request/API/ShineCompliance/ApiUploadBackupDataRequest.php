<?php


namespace App\Http\Request\API\ShineCompliance;


use App\Http\Request\API\APIRequest;

class ApiUploadBackupDataRequest extends APIRequest
{
    public function rules()
    {
        return [
            'backup_id' => 'required|integer',
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
            'backup_id.required' => 'The backup_id field is required!',
            'file.required' => 'The text file is required!',
        ];
    }
}
