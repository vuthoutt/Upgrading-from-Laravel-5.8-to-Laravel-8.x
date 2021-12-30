<?php

namespace App\Http\Request\ShineCompliance\Property;

use Illuminate\Foundation\Http\FormRequest;

class HistoricalDocumentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable',
            'property_id' => 'required',
            'name' => 'required|string|max:255',
            'document_type' => 'required',
            'type_other' => 'nullable',
            'historic_date' => 'nullable|date_format:d/m/Y',
            'document' => 'nullable|file|max:100000',
            'is_external_ms' => 'nullable',
            'category' => 'required',
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
