<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckCodeEmailRequest extends JsonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|integer|digits_between:6,6',
            'email' => 'required|email|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'O campo código é obrigatório.',
            'code.integer' => 'O código deve ser um número inteiro.',
            'code.digits_between' => 'O código deve ter 6 dígitos.',
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex' => 'O nome deve conter apenas letras e espaços.',
            'name.string' => 'O campo nome deve ser uma string.',
            'name.min' => 'O nome deve ter pelo menos :min caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.string' => 'O campo email deve ser uma string.',
            'email.email' => 'O email deve ser um endereço de e-mail válido.',
        ];
    }
}