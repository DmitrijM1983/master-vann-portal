<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|required|max:30',
            'email' => 'required|email|unique:users',
            'password' => 'string|required|min:6|max:30',
            'role_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле имени обязательно для заполнения',
            'name.string' => 'Имя должно быть строкой',
            'name.max' => 'Имя не должно превышать 30 символов',

            'email.required' => 'Поле электронной почты обязательно для заполнения',
            'email.email' => 'Введите действительный адрес электронной почты',
            'email.unique' => 'Электронная почта уже используется',

            'password.required' => 'Поле пароля обязательно для заполнения',
            'password.string' => 'Пароль должен быть строкой',
            'password.min' => 'Пароль должен содержать не менее 6 символов',
            'password.max' => 'Пароль не должен превышать 30 символов',

            'role_id.required' => 'Выберите в качестве кого вы регистрируетесь'
        ];
    }
}
