<?php

namespace App\Packages\Questao\Request;

use Illuminate\Foundation\Http\FormRequest;

class AlternativaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'alternativas' => 'required|array',
            'alternativas.*.alternativa' => 'required|string',
            'alternativas.*.isCorreta' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'alternativas.required' => 'O campo alternativas é obrigatório',
            'alternativas.array' => 'O campo alternativas deve ser um array',
            'alternativas.*.alternativa.required' => 'O campo alternativa é obrigatório',
            'alternativas.*.alternativa.string' => 'O campo alternativa deve ser uma string',
            'alternativas.*.isCorreta.required' => 'O campo isCorreta é obrigatório',
            'alternativas.*.isCorreta.boolean' => 'O campo isCorreta deve ser um boolean',
        ];
    }
}