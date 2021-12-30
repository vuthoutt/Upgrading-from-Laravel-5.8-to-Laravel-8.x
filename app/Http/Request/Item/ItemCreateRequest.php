<?php

namespace App\Http\Request\Item;

use Illuminate\Foundation\Http\FormRequest;

class ItemCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'position' => 'nullable',
            'pagination_type' => 'nullable',
            'category' => 'nullable',
            'area_id' => 'required',
            'survey_id' => 'required',
            'property_id' => 'required',
            'location_id' => 'required',
            'item_id' => 'sometimes|required',
            'action' => 'sometimes',
            'name' => 'required|string|max:255',
            'item-type' => 'required',
            'accessibility' => 'required_if:item-type,174',
            'assessment' => 'nullable',
            'sample' => 'nullable',
            'sample-other' => 'nullable',
            'sample-other-comments' => 'nullable',
            'reasons' => 'nullable',
            'reasons-other' => 'nullable',
            'specificLocations1' => 'nullable',
            'specificLocations2' => 'nullable',
            'specificLocations3' => 'nullable',
            'specificLocations-other' => 'nullable',
            'productDebris' => 'nullable',
            'productDebris-other' => 'nullable',
            'abestosTypes' => 'nullable',
            'AsbestosTypeMore' => 'nullable',
            'asbestosQuantityValue' => 'nullable',
            'extent' => 'nullable',
            'AccessibilityVulnerability' => 'nullable',
            'AdditionalInformation' => 'nullable',
            'AdditionalInformation-Other' => 'nullable',
            'LicensedNonLicensed' => 'nullable',
            'rAndDElement' => 'nullable',
            'airtest' => 'nullable',
            'airtest-other' => 'nullable',
            'comments' => 'nullable',
            'assessmentTypeKey' => 'nullable',
            'assessmentDamageKey' => 'nullable',
            'assessmentTreatmentKey' => 'nullable',
            'assessmentAsbestosKey' => 'nullable',
            'total-MAS' => 'nullable',
            'pasPrimary' => 'nullable',
            'pasSecondary' => 'nullable',
            'pasLocation' => 'nullable',
            'pasAccessibility' => 'nullable',
            'pasExtent' => 'nullable',
            'pasNumber' => 'nullable',
            'pasHumanFrequency' => 'nullable',
            'pasAverageTime' => 'nullable',
            'pasType' => 'nullable',
            'pasMaintenanceFrequency' => 'nullable',
            'total-PAS' => 'nullable',
            'photoLocation' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096 ',
            'photoItem' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'photoAdditional' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'ActionsRecommendations' => 'nullable',
            'ActionsRecommendations_other' => 'nullable',
            'not_assessed' => 'nullable',
            'not_assessed_reason' => 'required_if:not_assessed,2'

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
