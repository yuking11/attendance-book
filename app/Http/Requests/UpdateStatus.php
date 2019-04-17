<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatus extends FormRequest
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
            'calendar_id' => 'required|numeric',
            'member_id' => 'required|numeric',
            'status' => 'required|numeric',
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
            'calendar_id' => '日付',
            'member_id' => 'メンバー',
            'status' => '状態',
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
            'category_id.required' => ':attributeは入力必須です。',
            'category_id.numeric' => ':attributeは数値です。',
            'member_id.required' => ':attributeは入力必須です。',
            'member_id.numeric' => ':attributeは数値です。',
            'status.required' => ':attributeは入力必須です。',
            'status.numeric' => ':attributeは数値です。',
        ];
    }
}
