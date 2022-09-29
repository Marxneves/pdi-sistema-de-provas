<?php

namespace App\Packages\Tema\Request;

use Illuminate\Foundation\Http\FormRequest;

class TemaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nome' => 'required|string',
            'slugname' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'nome.string' => 'O campo nome deve ser uma string',
            'slugname.required' => 'O campo slugname é obrigatório',
            'slugname.string' => 'O campo slugname deve ser uma string',
        ];
    }
}