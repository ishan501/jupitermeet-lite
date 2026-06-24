<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileBasicRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'min:3', 'max:25'],
            'last_name' => ['required', 'string', 'min:3', 'max:25'],
            'username' => ['required', 'string', 'min:3', 'max:20', 'alpha_dash', 'unique:users,username,' . ($this->route('id') ?? $this->user()->id)],
            'avatar' => 'nullable|file|mimes:png,jpg|max:2048',
        ];
    }
}