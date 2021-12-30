<?php

namespace App\Http\Request\ShineCompliance\User;

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
            'username' => 'nullable',
            'password' => [
                'nullable'    // must contain at least one digit
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
            'analyst_access' => 'nullable',
            'avatar' => 'nullable|file|mimes:jpeg,bmp,png,jpg|max:4096',
            'equipment_inventories' => 'nullable',
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
            'user-name.regex'   => 'Please remove space in username field!',
            'department-other.unique'   => 'This department name is exist . Please try another !',
            'g-recaptcha-response.required'   => 'Please submit the captcha !',
        ];
    }

}
