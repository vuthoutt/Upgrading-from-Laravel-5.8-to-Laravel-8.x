<?php

namespace App\Http\Request\ShineCompliance\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class DocumentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable',
            'property_id' => 'required',
            'category_id' => 'required',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'type_other' => 'nullable',
            'date' => 'nullable|date_format:d/m/Y',
            'document' => 'nullable|file|max:4096',
            'parent_type' => 'nullable',
            'property_system' => 'nullable',
            'property_programme' => 'nullable',
            'property_equipment' => 'nullable',
            'enforcement_deadline' => 'nullable|date_format:d/m/Y',
            'document_status' => 'nullable',
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
