<?php

namespace App\Packages\Questao\Request;

use Illuminate\Foundation\Http\FormRequest;

class QuestaoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'temaSlugname' => 'required|string',
            'pergunta' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'temaSlugname.required' => 'O campo temaSlugname é obrigatório',
            'temaSlugname.string' => 'O campo temaSlugname deve ser uma string',
            'pergunta.required' => 'O campo pergunta é obrigatório',
            'pergunta.string' => 'O campo pergunta deve ser uma string',
        ];
    }
}