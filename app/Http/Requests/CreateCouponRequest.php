<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:128'],
            'code' => ['required', 'alpha_dash', 'min:3', 'max:128', 'unique:coupons,code'],
            'type' => ['required', 'integer', 'between:0,1'],
            'days' => ['required_if:type,1', 'nullable', 'integer', 'min:-1', 'max:3650'],
            'percentage' => ['required_if:type,0', 'nullable', 'numeric', 'min:1', 'max:99.99'],
            'quantity' => ['required', 'integer'],
        ];
    }
}
