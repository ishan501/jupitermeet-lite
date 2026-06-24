<?php

namespace App\Http\Requests;

use App\Rules\ValidateJsonFile;
use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageRequest extends FormRequest
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
            'code' => 'required|string|unique:languages|max:10',
            'name' => 'required|string|min:3|max:50',
            'direction' => 'required|string|in:ltr,rtl',
            'default' => 'required|string|in:no,yes',
            'status' => 'required|string|in:active,inactive',
            'file' => ['required', 'file', 'max:1024', new ValidateJsonFile],
        ];
    }
}
