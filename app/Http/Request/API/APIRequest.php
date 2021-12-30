<?php

namespace App\Http\Request\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use App\Helpers\APICommonHelpers;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class APIRequest extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $messages = implode(' ', Arr::flatten($errors));

        return Response::json(APICommonHelpers::makeError($messages), 400);
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(Response::json(APICommonHelpers::makeError($validator->errors()->first()), 422));
    }
}
