<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaystackRequest extends FormRequest
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
            'PAYSTACK' => ['required', 'integer', 'between:0,1'],         
            'PAYSTACK_SECRET_KEY' => ['required_if:PAYSTACK,1'],
        ];
    }

    public function attributes()
    {
        return [
            'PAYSTACK' => __('Paystack '),
            'PAYSTACK_SECRET_KEY' => __('Paystack Secret Key'),
        ];
    }
}