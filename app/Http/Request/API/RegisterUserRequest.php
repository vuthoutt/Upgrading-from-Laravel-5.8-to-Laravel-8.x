<?php

namespace App\Http\Request\API;


class RegisterUserRequest extends APIRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tbl_users',
            'password' => 'required|string|min:6|confirmed',
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
//            'username.required' => 'Username is required!',
//            'username.exists'   => 'Username does not exist !',
//            'password.required' => 'Password is required!'
        ];
    }


}
