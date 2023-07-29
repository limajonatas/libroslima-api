<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidateRequest extends JsonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //o nome deve acertar apenas letras e espaços (mas nao no inicio),
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/'
            ],
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:6',
            'nickname' => [
                'string',
                'min:3',
                'max:20',
                'unique:users,nickname',
                'regex:/^(?!.*(?:[-_]){2})[a-z\d]+(?:[-_][a-z\d]+)*$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex' => 'O nome deve conter apenas letras e espaços.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.min' => 'O nome deve ter pelo menos :min caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.string' => 'O campo email deve ser uma string.',
            'email.email' => 'O email deve ser um endereço de e-mail válido.',
            'email.unique' => 'Este endereço de e-mail já está em uso.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'nickname.string' => 'O campo apelido deve ser uma string.',
            'nickname.min' => 'O apelido deve ter pelo menos :min caracteres.',
            'nickname.max' => 'O apelido não deve ter mais de :max caracteres.',
            'nickname.unique' => 'Este apelido já está em uso.',
            'nickname.regex' => 'O apelido deve conter apenas letras minúsculas, números, traços e sublinhados.',
        ];
    }
}
