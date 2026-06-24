<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNodejsSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'KEY_PATH' => 'required|max:100',
            'CERT_PATH' => 'required|max:100',
            'PORT' => 'required|string|min:2|max:4',
        ];
    }

    public function attributes()
    {
        return [
            'KEY_PATH' => __('SSL Key Path'),
            'CERT_PATH' => __('SSL Certificate Path'),
            'PORT' => __('Port'),
        ];
    }
}