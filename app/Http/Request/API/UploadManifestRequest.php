<?php

namespace App\Http\Request\API;


class UploadManifestRequest extends APIRequest
{
    public function rules()
    {
        return [
            'surveyDetailId' => 'required|integer',
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
