<?php

namespace App\Http\Request\WorkRequest;

use Illuminate\Foundation\Http\FormRequest;

class WorkCreateRequest extends FormRequest
{
    public function rules()
    {
//            'major_document' => 'required_without:id',
        return [
            'id' => 'sometimes',
            'asbestos_lead' => 'required',
            'type' => 'required',
            'wr_type' => 'required',
            'sor_id' => 'nullable',
            'major_type' => 'nullable',
            'major_document' => 'nullable|file',
            'priority' => 'required',
            'priority_reason' => 'nullable|string|max:255',
            'contractor' => 'required_if:wr_type,35,57',
            'property_id' => 'required_unless:wr_type,35,57',
            'property_contact' => 'nullable',
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'telephone' => 'nullable',
            'mobile' => 'nullable',
            'email' => 'nullable',
            'site_occupied' => 'nullable',
            'site_availability' => 'nullable',
            'security_requirements' => 'nullable',
            'parking_arrangements' => 'nullable',
            'parking_arrangements_other' => 'nullable',
            'electricity_availability' => 'nullable',
            'water_availability' => 'nullable',
            'ceiling_height' => 'nullable',
            'scope_of_work' => 'nullable',
            'reported_by' => 'nullable',
            'access_note' => 'nullable',
            'location_note' => 'nullable',
            'sor_id' => 'nullable',
            'enclosure_size' => 'nullable',
            'duration_of_work' => 'nullable',
            'isolation_required' => 'nullable',
            'isolation_required_comment' => 'nullable|string|max:255',
            'decant_required' => 'nullable',
            'decant_required_comment' => 'nullable|string|max:255',
            'reinstatement_requirements' => 'nullable',
            'number_of_rooms' => 'nullable',
            'unusual_requirements' => 'nullable',
            'site_hs' => 'nullable',
            'hight_level_access' => 'nullable',
            'hight_level_access_comment' => 'nullable|string|max:255',
            'max_height' => 'nullable',
            'max_height_comment' => 'nullable|string|max:255',
            'loft_spaces' => 'nullable',
            'loft_spaces_comment' => 'nullable|string|max:255',
            'floor_voids' => 'nullable',
            'floor_voids_comment' => 'nullable|string|max:255',
            'basements' => 'nullable',
            'basements_comment' => 'nullable|string|max:255',
            'ducts' => 'nullable',
            'ducts_comment' => 'nullable|string|max:255',
            'lift_shafts' => 'nullable',
            'lift_shafts_comment' => 'nullable|string|max:255',
            'light_wells' => 'nullable',
            'light_wells_comment' => 'nullable|string|max:255',
            'confined_spaces' => 'nullable',
            'confined_spaces_comment' => 'nullable|string|max:255',
            'fumes_duct' => 'nullable',
            'fumes_duct_comment' => 'nullable|string|max:255',
            'pm_good' => 'nullable',
            'pm_good_comment' => 'nullable|string|max:255',
            'fragile_material' => 'nullable',
            'fragile_material_comment' => 'nullable|string|max:255',
            'hot_live_services' => 'nullable',
            'hot_live_services_comment' => 'nullable|string|max:255',
            'pieons' => 'nullable',
            'pieons_comment' => 'nullable|string|max:255',
            'vermin' => 'nullable',
            'vermin_comment' => 'nullable|string|max:255',
            'biological_chemical' => 'nullable',
            'biological_chemical_comment' => 'nullable|string|max:255',
            'vulnerable_tenant' => 'nullable',
            'vulnerable_tenant_comment' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
            'document' => 'nullable|file',
            'duration_number_test' => 'nullable',
            'email_cc' =>'nullable',
            'compliance_type' =>'required',
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
            'password.required' => 'Password is required!',
            'password.regex' => 'Password must have at least one lowercase, uppercase and number !',
            'contractor.required_if' => 'Please select a contractor !',
            'property_id.required_unless' => 'The property field is required.',
            'compliance_type.required' => 'The work request type field is required.',
            'wr_type.required' => '',
        ];
    }

}
