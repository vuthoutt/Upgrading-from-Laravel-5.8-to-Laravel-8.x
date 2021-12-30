<?php


namespace App\Http\Request\ShineCompliance\Assessment;


use Illuminate\Foundation\Http\FormRequest;

class CreateAssessmentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'property_id' => 'nullable',
            'type' => 'nullable',
            'due_date' => 'nullable|date_format:d/m/Y',
            'started_date' => 'nullable|date_format:d/m/Y',
            'assess_start_date' => 'nullable|date_format:d/m/Y',
            'assess_start_time' => 'nullable',
            'assess_finish_date' => 'nullable|date_format:d/m/Y',
            'lead_by' => 'required',
            'clientKey' => 'required',
            'second_lead_by' => 'nullable',
            'assessor_id' => 'required',
            'quality_checker' => 'required',
            'project_id' => 'nullable',
//            'work_request_id' => 'nullable',
            'setting_equipment_details' => 'nullable',
            'setting_show_vehicle_parking' => 'nullable',
            'setting_property_size_volume' => 'nullable',
            'setting_fire_safety' => 'nullable',
            'setting_hazard_photo_required' => 'nullable',
            'setting_non_conformities' => 'nullable',
            'setting_assessors_note_required' => 'nullable',
            'list_system' => 'nullable',
            'list_equipment' => 'nullable',
            'list_hazard' => 'nullable',
            'list_exist' => 'nullable',
            'list_assembly_point' => 'nullable',
            'list_vehicle' => 'nullable',
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
