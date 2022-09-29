<?php

namespace App\Packages\Prova\Request;

use Illuminate\Foundation\Http\FormRequest;

class EnviarProvaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'respostas' => 'required|array',
            'respostas.*.questaoId' => 'required',
            'respostas.*.respostaAluno' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'respostas.required' => 'Respostas é obrigatório',
            'respostas.array' => 'Respostas deve ser um array de objetos com id da questao e respostas do aluno',
            'respostas.*.questaoId.required' => 'O id da questão é obrigatório',
            'respostas.*.respostaAluno.required' => 'A resposta do aluno é obrigatória',
            'respostas.*.respostaAluno.string' => 'A rResposta do aluno deve ser uma string',
        ];
    }
}