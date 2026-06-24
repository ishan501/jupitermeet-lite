<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
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
            'id' => 'required',
            'first_name' => 'nullable|min:3|max:25',
            'last_name' => 'nullable|min:3|max:25',
            'username' => 'nullable|unique:users|min:3|max:20|alpha_dash',
            'email' => 'nullable|email|unique:users|min:3|max:50',
            'password' => 'nullable|string|min:6|max:50',
            'status' => 'nullable|in:active,inactive'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'data' => '',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
