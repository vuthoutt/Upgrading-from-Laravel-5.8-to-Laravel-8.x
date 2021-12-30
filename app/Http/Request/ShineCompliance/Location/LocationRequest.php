<?php

namespace App\Http\Request\ShineCompliance\Location;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class LocationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'area_id' => 'required',
            'survey_id' => 'required',
            'property_id' => 'required',
            'reference' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'location-state' => 'required',
            'location-photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'photo-reference' => 'nullable',
            'reasons-na' => 'nullable',
            'reasons-na-other' => 'nullable',
            'Ceiling-Void' => 'nullable',
            'Ceiling-Void-type' => 'nullable',
            'Ceiling-Void-type-other' => 'nullable',
            'Cavities' => 'nullable',
            'Cavities-type' => 'nullable',
            'Cavities-type-other' => 'nullable',
            'Risers' => 'nullable',
            'Risers-type' => 'nullable',
            'Risers-type-other' => 'nullable',
            'Ducting' => 'nullable',
            'Ducting-type' => 'nullable',
            'Ducting-type-other' => 'nullable',
            'Floor-Void' => 'nullable',
            'Floor-Void-type' => 'nullable',
            'Floor-Void-type-other' => 'nullable',
            'Pipework' => 'nullable',
            'Pipework-type' => 'nullable',
            'Pipework-type-other' => 'nullable',
            'Boxing' => 'nullable',
            'Boxing-type' => 'nullable',
            'Boxing-type-other' => 'nullable',
            'Ceiling' => 'nullable',
            'Ceiling-other' => 'nullable',
            'Walls' => 'nullable',
            'Walls-other' => 'nullable',
            'Floor' => 'nullable',
            'Floor-other' => 'nullable',
            'Doors' => 'nullable',
            'Doors-other' => 'nullable',
            'Windows' => 'nullable',
            'Windows-other' => 'nullable',
            'location-comment' => 'nullable',
            'not_assessed' => 'nullable',
            'not_assessed_reason' => 'nullable'
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
