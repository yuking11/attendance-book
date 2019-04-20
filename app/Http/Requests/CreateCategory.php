<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategory extends FormRequest
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
             'data.*.name' => 'カテゴリー名',
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
         ];
     }
}
