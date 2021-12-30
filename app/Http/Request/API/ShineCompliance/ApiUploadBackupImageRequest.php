<?php


namespace App\Http\Request\API\ShineCompliance;


use App\Http\Request\API\APIRequest;

class ApiUploadBackupImageRequest extends APIRequest
{
    public function rules()
    {
        return [
            'backup_id' => 'required|integer',
            'app_id' => 'required|integer',
            'image_type' => 'required',
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
            'app_id.required' => 'The app_id field is required!',
            'image_type.required' => 'The type field is required!',
            'file.required' => 'The file data is required!',
        ];
    }
}
