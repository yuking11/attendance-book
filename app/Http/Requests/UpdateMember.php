<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMember extends FormRequest
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
            '*.id' => 'required|numeric',
            '*.name' => 'required',
            '*.category_id' => 'required|numeric',
            '*.sort' => 'nullable|numeric',
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
            '*.id' => 'ID',
            '*.name' => '名前',
            '*.category_id' => 'カテゴリ',
            '*.sort' => '表示順',
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
            '*.id.required' => ':attributeは入力必須です。',
            '*.id.numeric' => ':attributeは数値です。',
            '*.name.required' => ':attributeは入力必須です。',
            '*.category_id.required' => ':attributeは入力必須です。',
            '*.category_id.numeric' => ':attributeは数値で入力します。',
            '*.sort.numeric' => ':attributeは数値で入力します。',
        ];
    }
}
