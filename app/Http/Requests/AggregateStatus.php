<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AggregateStatus extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start' => 'required|date',
            'end' => 'date|after_or_equal:start',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return [array]
     */
    public function attributes()
    {
        return [
            'start' => '開始日',
            'end' => '終了日',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start.required' => ':attributeは入力必須です。',
            'start.date' => ':attributeには有効な日付を指定してください。',
            'end.date' => ':attributeには有効な日付を指定してください。',
            'end.after_or_equal' => ':attributeには、:date以降の日付を指定してください。',
        ];
    }
}
