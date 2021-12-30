<?php


namespace App\Http\Request\API\ShineCompliance;


use App\Http\Request\API\APIRequest;

class ApiUploadImageRequest extends APIRequest
{
    public function rules()
    {
        return [
            'manifest_id' => 'required|integer',
            'assess_id' => 'required|integer',
            'image_type' => 'required|string',
            'file' => 'required|file|mimes:jpeg,bmp,png,jpg',
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
