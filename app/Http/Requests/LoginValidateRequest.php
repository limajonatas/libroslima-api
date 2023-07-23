<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginValidateRequest extends JsonRequest
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
            'user' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'remember_me' => 'required|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'user.required' => 'O campo usuário é obrigatório.',
            'user.string' => 'O usuário deve ser uma string.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'remember_me.required' => 'O campo lembrar-me é obrigatório.',
            'remember_me.boolean' => 'O campo lembrar-me deve ser um booleano.',
        ];
    }
}