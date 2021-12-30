<?php

namespace App\Http\Request\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'client_id' => 'required',
            'client_type' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'departments' => 'required',
            'department-other' => 'nullable|unique:tbl_departments,name|required_if:departments,-1',
            'username' => 'required|string|max:255|regex:/^\S*$/u|unique:tbl_users,username',
            'password' => [
                            'required',
                            'string',
                            'min:8',             // must be at least 8 characters in length
                            'regex:/[a-z]/',      // must contain at least one lowercase letter
                            'regex:/[A-Z]/',      // must contain at least one uppercase letter
                            'regex:/[0-9]/',      // must contain at least one digit
                        ],
            'email' => [
                            'required',
                            'string',
                            'max:255',
                            'email',
                            'unique:tbl_users,email'
                        ],
            'mobile' => 'nullable|string|max:20',
            'telephone' => 'nullable|string|max:20',
            'job-title' => 'nullable|string|max:255',
            'signature' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'role' => 'required_if:client_type,==,0',
            'asbestos-awareness' => 'nullable|date_format:d/m/Y',
            'shine-asbestos' => 'nullable|date_format:d/m/Y',
            'notes' => 'nullable|string|max:255',
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
