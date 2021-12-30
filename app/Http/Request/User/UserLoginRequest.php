<?php

namespace App\Http\Request\User;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => 'required|exists:tbl_users',
            'password' => 'required',
            'remember' => 'nullable'
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
            'username.required' => 'Username is required!',
            'username.exists'   => 'Username does not exist !',
            'password.required' => 'Password is required!'
        ];
    }

}
