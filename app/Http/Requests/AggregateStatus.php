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
            'start' => 'required|date_format:Y-m-d',
            'end' => 'date_format:Y-m-d|after_or_equal:start',
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
            'start.date_format' => 'YYYY-MM-DD形式で正しい日付を指定してください。',
            'end.date_format' => 'YYYY-MM-DD形式で正しい日付を指定してください。',
            'end.after_or_equal' => ':attributeには、:date以降の日付を指定してください。',
        ];
    }
}
