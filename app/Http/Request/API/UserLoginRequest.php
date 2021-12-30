<?php

namespace App\Http\Request\API;


class UserLoginRequest extends APIRequest
{
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
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
            'email.required' => 'Username is required!',
            'password.required' => 'Password is required!'
        ];
    }

}
