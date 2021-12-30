<?php

namespace App\Http\Request\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganisationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'name' => [
                        'nullable',
                        'string',
                        'max:255'
                    ],
            'address1' => 'nullable|max:255',
            'address2' => 'nullable|max:255',
            'address3' => 'nullable|max:255',
            'address4' => 'nullable|max:255',
            'address5' => 'nullable|max:255',
            'country' =>  'nullable|max:255',
            'postcode' => 'nullable',
            'ukas_reference' => 'sometimes|max:255',
            'telephone' => 'nullable|string|max:20',
            'mobile' => 'nullable|max:20',
            'logo' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'ukas' => 'sometimes|nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'email' => [
                            'nullable',
                            'string',
                            'max:255',
                            'email',
                            'unique:tbl_clients,email,'.$this->client_id
                        ],

            'email_notification' => 'nullable|string|max:255',
            'fax' => 'nullable|max:255',
            'key_contact' => '',
            'account_management_email' => 'sometimes|max:255',
            'type_surveying' => 'sometimes',
            'type_removal' => 'sometimes',
            'type_demolition' => 'sometimes',
            'type_analytical' => 'sometimes',
            'contractor_setup_id' => 'nullable',
            'removal_licence_reference' => 'nullable',
            'type_fire_equipment' => 'sometimes',
            'type_fire_risk' => 'sometimes',
            'type_fire_remedial' => 'sometimes',
            'type_independent_survey' => 'sometimes',
            'type_legionella_risk' => 'sometimes',
            'type_water_testing' => 'sometimes',
            'type_water_remedial' => 'sometimes',
            'type_temperature' => 'sometimes',
            'type_hs_assessment' => 'sometimes',
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
            'name.unique'   => 'This organisation name is exist . Please try another name!',
        ];
    }

}
