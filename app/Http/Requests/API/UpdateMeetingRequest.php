<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateMeetingRequest extends FormRequest
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
            'id' => 'required',
            'title' => 'nullable|min:3|max:100',
            'description' => 'max:1000',
            'password' => 'nullable|min:4|max:8',
            'date' => '',
            'time' => '',
            'timezone' => 'max:100',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('Please enter a meeting title.'),
            'title.min' => __('The title must be at least 3 characters.'),
            'title.max' => __('The title may not be greater than 100 characters.'),
            'description.max' => __('The description may not be greater than 1000 characters.'),
            'password.min' => __('Password must be at least 4 characters.'),
            'password.max' => __('Password may not be greater than 8 characters.'),
            'timezone.max' => __('The timezone may not be greater than 100 characters.'),
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
