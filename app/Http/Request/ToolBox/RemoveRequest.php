<?php

namespace App\Http\Request\ToolBox;

use Illuminate\Foundation\Http\FormRequest;

class RemoveRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'zone' => 'required',
            'reason-content' => 'required|max:255'
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
            'reason-content.required' => 'Please fill reason for removing action'
        ];
    }

}
