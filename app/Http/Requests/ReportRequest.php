<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'user_id' => 'required',
            'service' => 'required',
            'price' => 'required',
            'date' => 'required',
            'materials_price' => 'required',
            'transports_price' => 'required',
            'other_price' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'service.required' => 'Укажите оказанную услугу',
            'price.required' => 'Укажите итоговую стоимость услуги',
            'date.required' => 'Укажите дату',
            'materials_price.required' => 'Укажите стоимость материалов',
            'transports_price.required' => 'Укажите стоимость транспортных расходов',
        ];

    }
}
