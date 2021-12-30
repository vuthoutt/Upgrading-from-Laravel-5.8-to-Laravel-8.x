<?php

namespace App\Http\Request\ShineCompliance\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class SystemRequest extends FormRequest
{
    public function rules()
    {
        return [
            'assess_id' => 'nullable',
            'property_id' => 'nullable',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'classification' => 'required',
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
