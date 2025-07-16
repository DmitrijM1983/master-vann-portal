<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Введите имя',
            'middle_name.required' => 'Введите отчество',
            'last_name.required' => 'Введите фамилию',
            'phone.required' => 'Введите телефон'
        ];
    }
}
