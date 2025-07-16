<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterInfoRequest extends FormRequest
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
            'master_photo' => 'nullable|mimes:jpeg,jpg,png|max:5000',
            'experience' => 'nullable|max:4',
            'guarantee' => 'nullable',
            'description' => 'nullable|string|max:2000'
        ];
    }

    public function messages(): array
    {
        return [
            'master_photo.mimes' => 'Фотография должна иметь разрешение jpeg,jpg или png',
            'master_photo.max' => 'Максимальный размер файла 5MБ',
            'experience.max' => 'Введите год начала работы например, 2019',
            'experience.min' => 'Введите год начала работы например, 2019',
            'description' => 'Текст не должен превышать 2000 символов'
        ];
    }
}
