<?php

namespace App\Http\Request\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => [
                            'required',
                            'confirmed',
                            'string',
                            'min:8',             // must be at least 8 characters in length
                            'regex:/[a-z]/',      // must contain at least one lowercase letter
                            'regex:/[A-Z]/',      // must contain at least one uppercase letter
                            'regex:/[0-9]/',      // must contain at least one digit
                        ],
            'first_login' => 'nullable'
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
            'password.required' => 'Password is required!',
            'password.regex' => 'Password must have at least one lowercase, uppercase and number !',
        ];
    }

}
