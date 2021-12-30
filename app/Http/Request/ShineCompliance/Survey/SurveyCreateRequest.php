<?php

namespace App\Http\Request\ShineCompliance\Survey;

use Illuminate\Foundation\Http\FormRequest;

class SurveyCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'surveyType' => 'required',
            'date' => 'nullable|date_format:d/m/Y',
            'due-date' => 'nullable|date_format:d/m/Y',
            'sv-start-date' => 'nullable|date_format:d/m/Y',
            'sv-finish-date' => 'nullable|date_format:d/m/Y',
            'published-date' => 'sometimes|nullable|date_format:d/m/Y',
            'completed-date' => 'sometimes|nullable|date_format:d/m/Y',
            'sample-sent-to-lab-date' => 'nullable|date_format:d/m/Y',
            'sample-received-from-lab-date' => 'nullable|date_format:d/m/Y',
            'clientKey' => 'required',
            'leadBy' => 'required',
            'secondLeadBy' => 'nullable',
            'surveyor' => 'nullable',
            'secondSurveyor' => 'nullable',
            'cad_tech' => 'nullable',
            'consultantKey' => 'nullable',
            'projectKey' => 'nullable',
            'qualityKey' => 'nullable',
            'analystKey' => 'nullable',
            'priorityAssessmentRequired' => 'sometimes',
            'constructionDetailsRequired' => 'sometimes',
            'locationVoidInvestigationsRequired' => 'sometimes',
            'locationConstructionDetailsRequired' => 'sometimes',
            'RDinManagementAllowed' => 'sometimes',
            'licenseStatusRequired' => 'sometimes',
            'photosRequired' => 'sometimes',
//            'external_laboratory' => 'sometimes',
            'propertyPlanPhoto' => 'sometimes',
            'ceiling_void' => 'sometimes',
            'floor_void' => 'sometimes',
            'cavities' => 'sometimes',
            'risers' => 'sometimes',
            'ducting' => 'sometimes',
            'boxing' => 'sometimes',
            'pipework' => 'sometimes',
            'list_area' => 'nullable',
            'list_location' => 'nullable',
            'list_item' => 'nullable',
            'work_stream' => 'nullable',
            'cost' => 'nullable',
            'organisation_reference' => 'nullable'
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
            'projectKey.required' => 'Please select linked project (or create a new one) Before new survey creation.'
        ];
    }

}
