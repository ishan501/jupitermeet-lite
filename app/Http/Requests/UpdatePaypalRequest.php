<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaypalRequest extends FormRequest
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
            'PAYPAL' => ['required', 'integer', 'between:0,1'],
            'PAYPAL_MODE' => ['required_if:PAYPAL,1'],
            'PAYPAL_CLIENT_ID' => ['required_if:PAYPAL,1'],
            'PAYPAL_SECRET' => ['required_if:PAYPAL,1'],
            'PAYPAL_WEBHOOK_ID' => ['required_if:PAYPAL,1'],
        ];
    }

     public function attributes()
    {
        return [
            'PAYPAL' => __('PayPal '),
            'PAYPAL_MODE' => __('PayPal Key'),
            'PAYPAL_CLIENT_ID' => __('PayPal Clienrt ID'),
            'PAYPAL_SECRET' => __('PayPal Secret Key'),
            'PAYPAL_WEBHOOK_ID' => __('PayPal Webhook ID'),
        ];
    }
}