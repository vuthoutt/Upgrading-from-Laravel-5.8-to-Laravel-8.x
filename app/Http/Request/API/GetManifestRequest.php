<?php

namespace App\Http\Request\API;


class GetManifestRequest extends APIRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
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
        ];
    }


}
