<?php

namespace App\Http\Request\API;


class CheckSurveyRequest extends APIRequest
{
    public function rules()
    {
        return [
            'userID' => 'required|integer',
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
