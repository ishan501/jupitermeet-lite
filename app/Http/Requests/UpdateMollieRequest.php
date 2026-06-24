<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMollieRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'MOLLIE' => ['required', 'integer', 'between:0,1'],
            'MOLLIE_API_KEY' => ['required_if:MOLLIE,1'],
        ];
    }

    public function attributes()
    {
        return [
            'MOLLIE' => __('Mollie '),
            'MOLLIE_API_KEY' => __('Mollie Secret Key'),
        ];
    }
}
