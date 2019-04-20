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
            'data.*.id' => 'required|numeric',
            'data.*.name' => 'required',
            'data.*.sort' => 'nullable|numeric',
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
            'data.*.id' => 'ID',
            'data.*.name' => 'カテゴリー名',
            'data.*.sort' => '表示順',
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
            'data.*.id.required' => ':attributeは入力必須です。',
            'data.*.id.numeric' => ':attributeは数値です。',
            'data.*.name.required' => ':attributeは入力必須です。',
            'data.*.sort.numeric' => ':attributeは数値で入力します。',
        ];
    }
}
