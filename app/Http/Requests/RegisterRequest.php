<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\RequiredIf;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateReCaptcha;


class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:25'],
            'last_name' => ['required', 'string', 'max:25'],
            'username' => ['required', 'string', 'max:20', 'unique:users', 'alpha_dash'],
            'email' => ['required', 'string', 'email:rfc,dns', 'min:3', 'max:50', 'unique:users'],
            'password' => ['required', 'min:6'],
            'terms' => ['accepted'],
            'g-recaptcha-response' => [new RequiredIf(getSetting('CAPTCHA_REGISTER_PAGE') == 'enabled'), new ValidateReCaptcha]
        ];
    }
}
