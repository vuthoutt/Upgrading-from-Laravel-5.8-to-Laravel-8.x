<?php

namespace App\Http\Request\API;


class CheckCompleteRequest extends APIRequest
{
    public function rules()
    {
        return [
            'manifestID' => 'required|integer',
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }


}
