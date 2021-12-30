<?php

namespace App\Http\Request\API;


class UploadImageRequest extends APIRequest
{
    public function rules()
    {
        return [
            'manifestID' => 'required|integer|exists:tbl_upload_manifest,id',
            'surveyID' => 'required',
            'type' => 'required',
            'objectID' => 'required',
            'objectData' => 'required|file|mimes:jpeg,bmp,png,jpg',
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
            'manifestID.required' => 'The manifestID field is required',
            'manifestID.exists' => 'The manifestID field is not exist in system, Please try again!',
            'objectID.required' => 'The objectID field is required',
            'surveyID.required' => 'The surveyID field is required',
            'objectData.required' => 'The objectData field is required',
        ];
    }


}
