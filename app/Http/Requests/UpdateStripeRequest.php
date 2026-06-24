<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStripeRequest extends FormRequest
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
            'STRIPE' => ['required', 'integer', 'between:0,1'],
            'STRIPE_KEY' => ['required_if:STRIPE,1'],
            'STRIPE_SECRET' => ['required_if:STRIPE,1'],
            'STRIPE_WH_SECRET' => ['required_if:STRIPE,1'],
        ];
    }

    public function attributes()
    {
        return [
            'STRIPE' => __('Stripe'),
            'STRIPE_KEY' => __('Stripe Publishable Key'),
            'STRIPE_SECRET' => __('Stripe Secret Key'),
            'STRIPE_WH_SECRET' => __('Stripe Signaling Secret'),
        ];
    }
}