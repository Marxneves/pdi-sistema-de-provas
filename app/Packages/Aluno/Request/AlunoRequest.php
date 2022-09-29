<?php

namespace App\Packages\Aluno\Request;

use Illuminate\Foundation\Http\FormRequest;

class AlunoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nome' => 'string|required',
        ];
    }

    public function messages()
    {
        return [
            'nome.string'=> 'O nome precisa ser uma string',
            'nome.required' => 'O campo nome é obrigatório'
        ];
    }
}