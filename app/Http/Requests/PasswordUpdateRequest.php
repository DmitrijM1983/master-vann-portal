<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Введите электронную почту',
            'email.email' => 'Введите действительный адрес электронной почты',

            'password.required' => 'Введите пароль',
            'password.min' => 'Пароль должен содержать не менее 6 символов',
            'password.confirmed' => 'Введенные пароли не совпадают'
        ];
    }
}
