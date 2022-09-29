<?php

namespace App\Packages\Prova\Request;

use Illuminate\Foundation\Http\FormRequest;

class ProvaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tema' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'tema.required' => 'O tema é obrigatório',
            'tema.string' => 'O tema deve ser uma string',
        ];
    }
}