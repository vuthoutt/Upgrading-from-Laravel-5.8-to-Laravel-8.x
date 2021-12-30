<?php

namespace App\Http\Request\API;


class GetSurveyDetailRequest extends APIRequest
{
    public function rules()
    {
        return [
            'userID' => 'required|integer',
            'surveyID' => 'required|integer',
            'appVersion' => 'nullable',
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
