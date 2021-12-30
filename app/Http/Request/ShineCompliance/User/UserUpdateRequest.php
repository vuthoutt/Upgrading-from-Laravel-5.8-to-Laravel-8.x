<?php

namespace App\Http\Request\ShineCompliance\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'departments' => 'required',
            'username' => 'nullable',
            'email' => [
                            'required',
                            'string',
                            'max:255',
                            'email',
                            'unique:tbl_users,email,'.$this->id
                        ],
            'mobile' => 'nullable|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'role' => 'required_if:client_type,==,0',
            'job-title' => 'nullable|string|max:255',
            'signature' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'avatar' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'site-operative' => 'sometimes',
            'asbestos-awareness' => 'nullable|date_format:d/m/Y',
            'shine-asbestos' => 'nullable|date_format:d/m/Y',
            'notes' => 'nullable|string|max:255',
            'equipment_inventories' => 'nullable',
            'housing_officer' => 'nullable',
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
            'role.required_if'   => 'The role field is required.',
        ];
    }

}
