<?php

namespace App\Http\Request\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectCreateRequest extends FormRequest
{
    public function rules()
    {
       return [
           'title' => 'required',
           'status' => 'required',
           'client_id' => 'required',
           'property_id' => 'required',
           'project_type' => 'required',
           'enquiry_date' => 'nullable|date_format:d/m/Y',
           'date' => 'nullable|date_format:d/m/Y',
           'due_date' => 'nullable|date_format:d/m/Y',
           'lead_key' => 'required',
           'contractor_not_required' => 'nullable',
           'second_lead_key' => 'nullable',
           'sponsor_lead_key' => 'nullable',
           'sponsor_id' => 'nullable',
           'job_no' => 'nullable',
           'rr_condition' => 'nullable',
           'survey_type' => 'nullable',
           'hazard_id' => 'nullable',
           'survey_id' => 'nullable',
           'document_id' => 'nullable',
           'comments' => 'nullable',
           'contractors' => 'nullable',
           'checked_contractors' => 'sometimes',
           'linked_project_type' => 'nullable',
           'linked_project_id' => 'nullable',
           'work_stream' => 'nullable',
           'risk_classification_id' => 'nullable',
           'assessment_id' => 'nullable',
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
