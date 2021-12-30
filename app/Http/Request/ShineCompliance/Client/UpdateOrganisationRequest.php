<?php

namespace App\Http\Request\ShineCompliance\Client;

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
            'account_management_email' => 'nullable|string|max:255',
            'type_surveying' => 'sometimes',
            'type_removal' => 'sometimes',
            'type_demolition' => 'sometimes',
            'type_analytical' => 'sometimes',
            'contractor_setup_id' => 'nullable',
            'removal_licence_reference' => 'nullable',

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
