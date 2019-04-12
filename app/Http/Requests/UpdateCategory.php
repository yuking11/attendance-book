<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategory extends FormRequest
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
            '*.name' => 'カテゴリー名',
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
            '*.sort.numeric' => ':attributeは数値で入力します。',
        ];
    }
}
