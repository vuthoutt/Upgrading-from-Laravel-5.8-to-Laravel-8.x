<?php

namespace App\Http\Request\Survey;

use Illuminate\Foundation\Http\FormRequest;

class SurveyInformationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'executivesummary' => 'nullable',
            'limitations' => 'nullable',
            'method' => 'nullable',
            'objectives_scope' => 'nullable',
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
