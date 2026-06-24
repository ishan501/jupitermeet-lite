<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyInformationRequest extends FormRequest
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
            'COMPANY_NAME' => 'required|string|max:50',
            'COMPANY_ADDRESS' => 'required|string|max:100',
            'COMPANY_CITY' => 'required|string|max:35',
            'COMPANY_STATE' => 'required|string|max:25',
            'COMPANY_POSTAL_CODE' => 'required|string|max:10',
            'COMPANY_COUNTRY' => 'required|string|max:25',
            'COMPANY_PHONE' => 'required|string|max:15',
            'COMPANY_EMAIL' => 'required|string|max:62',
            'COMPANY_TAX_ID' => 'required|string|max:25',
        ];
    }

    public function attributes()
    {
        return [
            'COMPANY_NAME' => __('Company Name'),
            'COMPANY_ADDRESS' => __('Company Address'),
            'COMPANY_CITY' => __('Company City'),
            'COMPANY_STATE' => __('Company State/Region'),
            'COMPANY_POSTAL_CODE' => __('Company Postal code'),
            'COMPANY_COUNTRY' => __('Company Country'),
            'COMPANY_PHONE' => __('Company Phone'),
            'COMPANY_EMAIL' => __('Company Email'),
            'COMPANY_TAX_ID' => __('Company Tax Number'),
        ];
    }
}