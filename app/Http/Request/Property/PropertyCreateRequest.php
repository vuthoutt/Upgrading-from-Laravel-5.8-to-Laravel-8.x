<?php

namespace App\Http\Request\Property;

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
           'asset_use_primary' => 'nullable',
           'primaryusemore' => 'nullable',
           'asset_use_secondary' => 'nullable',
           'secondaryusemore' => 'nullable',
           'construction_age' => 'nullable',
           'construction_type' => 'nullable',
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
           'size_comments' => 'nullable',
           'photo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
           'core_code' => 'nullable',
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
           'town' => 'nullable',

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
