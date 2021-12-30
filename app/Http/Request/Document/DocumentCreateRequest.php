<?php

namespace App\Http\Request\Document;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentCreateRequest extends FormRequest
{
    public function rules()
    {
       return [
           'project_id' => 'sometimes',
           'doc_cat' => 'nullable',
           'contractor_key' => 'nullable',
           'client_id' => 'nullable',
           'added_by' => 'nullable',
           'name' => 'required',
           'type' => 'nullable',
           'doc_value' => 'nullable',
           'document_file' => 'required_without:project_id|file|mimes:jpeg,bmp,png,jpg|max:150000',
           'deadline' => 'nullable|date_format:d/m/Y',
           'contractors' => 'nullable',
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
