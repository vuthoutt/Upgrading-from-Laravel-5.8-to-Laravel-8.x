<?php

namespace App\Http\Request\ShineCompliance\Survey;

use Illuminate\Foundation\Http\FormRequest;

class SampleUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'reference' => 'required|max:255',
            'list_item_id' => '',
            'abestosTypes' => 'nullable',
            'AsbestosTypeMore' => 'nullable',
            'assessmentAsbestosKey' => 'nullable',
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
