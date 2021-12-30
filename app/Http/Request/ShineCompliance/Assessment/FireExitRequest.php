<?php


namespace App\Http\Request\ShineCompliance\Assessment;


use Illuminate\Foundation\Http\FormRequest;

class FireExitRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required', // TODO: validation
            'type' => 'required',
            'assess_id' => 'required',
            'property_id' => 'required',
            'area_id' => 'required',
            'area_reference' => 'required_if:area_id,-1',
            'area_description' => 'nullable',
            'location_id' => 'required',
            'location_reference' => 'required_if:location_id,-1',
            'location_description' => 'nullable',
            'accessibility' => 'nullable',
            'reason_na' => 'nullable',
            'photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'comment' => 'nullable',
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
            'area_reference.required_if' => 'The area reference field is required ',
            'location_reference.required_if' => 'The location reference field is required ',
        ];
    }
}
