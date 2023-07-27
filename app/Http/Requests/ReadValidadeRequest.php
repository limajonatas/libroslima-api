<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadValidadeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'timestamp' => 'required|date_format:Y-m-d H:i:s',
            'time_read' => 'required|integer|min:1', //to work with seconds
            'stopped_page' => 'required|integer|min:1',
            'comments' => 'string|max:255',
            'section_where_stopped' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'timestamp.required' => 'O campo momento é obrigatório',
            'timestamp.date_format' => 'O campo momento deve estar no formato Y-m-d H:i:s',
            'stopped_page.required' => 'O campo página em que parou é obrigatório',
            'stopped_page.integer' => 'O campo página parada deve ser um número inteiro',
            'stopped_page.min' => 'O campo página parada deve ser maior que 0',
            'comments.string' => 'O campo comentários deve ser uma string',
            'comments.max' => 'O campo comentários deve ter no máximo 255 caracteres',
            'section_where_stopped.string' => 'O campo seção onde parou deve ser uma string',
            'section_where_stopped.max' => 'O campo seção onde parou deve ter no máximo 255 caracteres',
            'time_read.required' => 'O campo tempo de leitura é obrigatório',
            'time_read.integer' => 'O campo tempo de leitura deve ser um número inteiro',
            'time_read.min' => 'O tempo de leitura é curto demais, The Flash! ',
        ];
    }
}
