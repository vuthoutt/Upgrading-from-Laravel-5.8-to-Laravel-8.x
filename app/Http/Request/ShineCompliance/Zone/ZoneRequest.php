<?php

namespace App\Http\Request\ShineCompliance\Zone;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ZoneRequest extends FormRequest
{
    public function rules()
    {
        return [
            'zone_id' => 'nullable',
            'zone_name' => 'required|max:255',
            'zone_image' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
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
            'zone_name.required'   => 'The name field is required.',
        ];
    }

}
