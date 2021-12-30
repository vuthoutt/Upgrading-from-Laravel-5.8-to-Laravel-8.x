<?php

namespace App\Http\Request\ShineCompliance\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropertyCreateRequest extends FormRequest
{
    public function rules()
    {
       return [
           'name' => 'required',
           'property_reference' => 'nullable',
           'pblock' => 'nullable',
           'riskType' => 'nullable',
           'programme_type' => 'nullable',
           'programme_type_other' => 'nullable',
           'client_id' => 'required',
           'zone_id' => 'required',
           'flat_number' => 'nullable|string|max:255',
           'building_name' => 'nullable|string|max:255',
           'street_number' => 'nullable|string|max:255',
           'street_name' => 'nullable|string|max:255',
           'address3' => 'nullable|string|max:255',
           'address4' => 'nullable|string|max:255',
           'address5' => 'nullable|string|max:255',
           'postcode' => 'max:20',
           'telephone' => 'nullable|max:20',
           'mobile' => 'nullable|max:20',
           'email' => 'nullable|email',
           'app_contact' => 'nullable',
           'team' => 'nullable',
           'property_status' => 'nullable',
           'property_occupied' => 'nullable',
           'asset_use_primary' => 'nullable',
           'primaryusemore' => 'nullable',
           'asset_use_secondary' => 'nullable',
           'secondaryusemore' => 'nullable',
           'construction_age' => 'nullable',
           'construction_materials' => 'nullable',
           'construction_materials-other' => 'nullable',
           'listed_building' => 'nullable',
           'listed_building_other' => 'nullable',
           'size_floors' => 'nullable',
           'size_staircases' => 'nullable',
           'size_lifts' => 'nullable',
           'size_floorsOther' => 'nullable',
           'size_staircasesOther' => 'nullable',
           'size_liftsOther' => 'nullable',
           'electricalMeter' => 'nullable',
           'gasMeter' => 'nullable',
           'loftVoid' => 'nullable',
           'size_bedrooms' => 'nullable',
           'size_net_area' => 'nullable',
           'size_gross_area' => 'nullable',
           'size_staircases_other' => 'nullable',
           'size_lifts_other' => 'nullable',
           'size_floors_other' => 'nullable',
           'parking_arrangements' => 'nullable',
           'parking_arrangements_other' => 'nullable',
           'nearest_hospital' => 'nullable',
           'restrictions_limitations' => 'nullable',
           'unusual_features' => 'nullable',
           'size_comments' => 'nullable',
           'photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
           'core_code' => 'nullable',
           'cluster_reference' => 'nullable',
           'service_area_id' => 'nullable',
           'parent_reference' => 'nullable',
           'estate_code' => 'nullable',
           'asset_class_id' => 'nullable',
           'asset_type_id' => 'nullable',
           'tenure_type_id' => 'nullable',
           'communal_area_id' => 'nullable',
           'responsibility_id' => 'nullable',
           'parent_id' => 'nullable',
           'ward_id' => 'nullable',
           'region_id' => 'nullable',
           'local_authority' => 'nullable',
           'division_id' => 'nullable',
           'building_category' => 'nullable',
           'is_compliance' => 'nullable',
           'town' => 'nullable',
           'vulnerable_occupant_type' => 'nullable',
           'avg_vulnerable_occupants' => 'nullable',
           'max_vulnerable_occupants' => 'nullable',
           'last_vulnerable_occupants' => 'nullable',
           'evacuation_strategy' => 'nullable',
           "stairs" => 'nullable',
           "stairs_other" => 'nullable',
           "floors" => 'nullable',
           "floors_other" => 'nullable',
           "wall_construction" => 'nullable',
           "wall_construction_other" => 'nullable',
           "wall_finish" => 'nullable',
           "wall_finish_other" => 'nullable',
           "floors_above" => 'nullable',
           "floors_above_other" => 'nullable',
           "floors_below" => 'nullable',
           "floors_below_other" => 'nullable',

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
            'email.unique'   => 'This email is exist . Please try another email !',
        ];
    }

}
