<?php


namespace App\Http\Request\ShineCompliance\IncidentReport;


use Illuminate\Foundation\Http\FormRequest;

class IncidentReportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'report_recorder' => 'nullable',
//            'asbestos_lead' => 'nullable',
            'type' => 'required',
            'date_of_report' => 'nullable',
            'time_of_report' => 'nullable',
            'date_of_incident' => 'nullable|date_format:d/m/Y',
            'time_of_incident' => 'nullable|date_format:H:i',
            'reported_by' => 'required',
            'reported_by_other' => 'required_if:reported_by,-1',
            'property_id' => 'nullable',
            'equipment_id' => 'nullable',
            'system_id' => 'nullable',
            'details' => 'required',
            'is_involved' => 'nullable',
            'involved' => 'nullable',
            'confidential' => 'nullable',
            'documents' => 'nullable',
            'category_of_works' => 'nullable',
            'is_risk_assessment' => 'nullable',
            'call_centre_team_member_name' => 'sometimes|required',
            'is_address_in_wcc' => 'nullable',
            'address_building_name' => 'nullable',
            'address_street_number' => 'nullable',
            'address_street_name' => 'nullable',
            'address_town' => 'nullable',
            'address_county' => 'nullable',
            'address_postcode' => 'nullable',
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
