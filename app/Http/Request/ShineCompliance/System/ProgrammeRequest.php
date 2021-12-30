<?php

namespace App\Http\Request\ShineCompliance\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ProgrammeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
//            'date_inspected' => 'nullable',
            'inspection_period' => 'required',
            'comment' => 'nullable',
            'photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
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
