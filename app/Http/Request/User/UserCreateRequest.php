<?php

namespace App\Http\Request\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user-name' => 'required|string|max:255|regex:/^\S*$/u|unique:tbl_users,username',
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:tbl_users',
            'mobile' => 'nullable|numeric',
            'telephone' => 'nullable|numeric',
            'organisation' => 'required',
            'department' => 'required',
            'department-other' => 'nullable|unique:tbl_departments,name|required_if:department,-1',
            'role' => 'required_if:organisation,==,1',
            // 'g-recaptcha-response' => 'required|captcha',
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
