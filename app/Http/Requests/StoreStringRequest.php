<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStringRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'value' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'value.required' => 'Missing "value" field',
            'value.string' => '"value" must be a string',
        ];
    }
}
