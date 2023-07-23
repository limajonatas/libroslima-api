<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookValidateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'author' => 'required|string',
            'pages' => 'required|integer',
            'how_many_times_read' => 'required|integer',
            'publisher_year' => 'integer|between:1900,2023',
            'genre' => 'string',
            'page_current' => 'integer',
            'synopsis' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O campo título é obrigatório',
            'title.string' => 'O campo título deve ser uma string',
            'author.required' => 'O campo autor é obrigatório',
            'author.string' => 'O campo autor deve ser uma string',
            'pages.required' => 'O campo páginas é obrigatório',
            'pages.integer' => 'O campo páginas deve ser um número inteiro',
            'how_many_times_read.required' => 'O campo quantas vezes lido é obrigatório',
            'how_many_times_read.integer' => 'O campo quantas vezes lido deve ser um número inteiro',
            'publisher_year.integer' => 'O campo ano de publicação deve ser um número inteiro',
            'publisher_year.between' => 'O campo ano de publicação deve estar entre 1900 e 2023',
            'genre.string' => 'O campo gênero deve ser uma string',
            'page_current.integer' => 'O campo página atual deve ser um número inteiro',
            'synopsis.string' => 'O campo sinopse deve ser uma string',
            'image.image' => 'O campo imagem deve ser uma imagem',
            'image.mimes' => 'O campo imagem deve ser uma imagem do tipo jpeg, png, jpg, gif ou svg',
            'image.max' => 'O campo imagem deve ter no máximo 2048 caracteres',
        ];
    }
}
