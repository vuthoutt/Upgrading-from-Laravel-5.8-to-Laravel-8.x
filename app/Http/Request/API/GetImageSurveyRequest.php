<?php

namespace App\Http\Request\API;


class GetImageSurveyRequest extends APIRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
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
        ];
    }


}
