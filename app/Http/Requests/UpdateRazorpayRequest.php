<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRazorpayRequest extends FormRequest
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
            'RAZORPAY' => ['required', 'integer', 'between:0,1'],
            'RAZORPAY_API_KEY' => ['required_if:RAZORPAY,1'],
            'RAZORPAY_SECRET_KEY' => ['required_if:RAZORPAY,1'],
        ];
    }

    public function attributes()
    {
        return [
            'RAZORPAY' => __('Razorpay '),
            'RAZORPAY_API_KEY' => __('Razorpay API Key'),
            'RAZORPAY_SECRET_KEY' => __('Razorpay Secret Key'),
        ];
    }
}