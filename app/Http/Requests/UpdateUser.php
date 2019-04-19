<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
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
            'id' => 'ユーザーID',
            'name' => 'ユーザー名',
            'email' => 'メールアドレス',
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
            'id.required' => ':attributeは入力必須です。',
            'id.numeric' => ':attributeは数値です。',
            'name.required' => ':attributeは入力必須です。',
            'name.string' => ':attributeは文字列を指定してください。',
            'name.max' => [
                'numeric' => ':attributeには、:max以下の数字を指定してください。',
            ],
            'name.unique' => ':attributeは既に存在しています。',
            'name.string' => ':attributeは数値です。',
            'email.required' => ':attributeは入力必須です。',
            'email.string' => ':attributeは文字列を指定してください。',
            'email.email'=> ':attributeには、有効なメールアドレスを指定してください。',
            'email.max' => [
                'numeric' => ':attributeには、:max以下の数字を指定してください。',
            ],
        ];
    }
}
