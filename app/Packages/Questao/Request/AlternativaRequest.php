<?php

namespace App\Packages\Questao\Request;

use Illuminate\Foundation\Http\FormRequest;

class AlternativaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'alternativas' => 'required|array',
            'alternativas.*.resposta' => 'required|string',
            'alternativas.*.isCorreta' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'alternativas.required' => 'O campo alternativas é obrigatório',
            'alternativas.array' => 'O campo alternativas deve ser um array',
            'alternativas.*.resposta.required' => 'O campo resposta é obrigatório',
            'alternativas.*.resposta.string' => 'O campo resposta deve ser uma string',
            'alternativas.*.isCorreta.required' => 'O campo isCorreta é obrigatório',
            'alternativas.*.isCorreta.boolean' => 'O campo isCorreta deve ser um boolean',
        ];
    }
}