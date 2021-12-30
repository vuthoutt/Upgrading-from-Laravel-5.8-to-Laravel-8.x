<?php

namespace App\Http\Request\ToolBox;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'document' => 'required|file|mimes:csv,txt'
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
