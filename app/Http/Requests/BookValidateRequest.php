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
            'authors' => 'required|array|min:1',
            'pages' => 'required|integer|min:1',
            'how_many_times_read' => 'required|integer|min:0',
            'publisher_year' => 'integer|between:1900,2023',
            'genre' => 'string',
            'page_current' => 'integer|min:0',
            'synopsis' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'rating' => 'integer|min:0|max:5',
            'opinion' => 'string',
            'read_start_date' => 'date',
            'read_end_date' => 'date',
            'last_read_complete' => 'date',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O campo título é obrigatório',
            'title.string' => 'O campo título deve ser uma string',
            'authors.required' => 'O campo autor(es) é obrigatório',
            'authors.array' => 'O campo autor(es) deve ser um array',
            'authors.min' => 'Deve haver pelo menos um autor',
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
            'rating.integer' => 'O campo avaliação deve ser um número inteiro',
            'rating.min' => 'O campo avaliação deve ser no mínimo 0',
            'rating.max' => 'O campo avaliação deve ser no máximo 5',
            'opinion.string' => 'O campo opinião deve ser uma string',
        ];
    }
}