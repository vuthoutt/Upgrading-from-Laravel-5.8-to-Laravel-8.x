<?php

namespace App\Http\Request\API;


class UploadDataRequest extends APIRequest
{
    public function rules()
    {
        return [
            'manifestID' => 'required|integer|exists:tbl_upload_manifest,id',
            'type' => 'required',
            // 'objectID' => 'required|integer',
            'objectData' => 'required|json',
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
            'objectData.required' => 'The objectData field is required',
        ];
    }


}
