<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMember extends FormRequest
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
            'data.*.name' => 'required',
            'data.*.category_id' => 'required|numeric',
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
             'data.*.name' => '名前',
             'data.*.category_id' => 'カテゴリ',
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
             'data.*.name.required' => ':attributeは入力必須です。',
             'data.*.category_id.required' => ':attributeは選択必須です。',
             'data.*.category_id.numeric' => ':attributeは数値で入力します。',
         ];
     }
}
