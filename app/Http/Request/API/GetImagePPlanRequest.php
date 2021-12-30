<?php

namespace App\Http\Request\API;


class GetImagePPlanRequest extends APIRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'planID' => 'required|integer',
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
            'planID.required' => 'The planID field is required!'
        ];
    }


}
