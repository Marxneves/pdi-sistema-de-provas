<?php

namespace App\Packages\Aluno\Response;


use App\Packages\Aluno\Domain\Model\Aluno;

class AlunoResponse
{
    public static function item(Aluno $aluno): array
    {
        return [
            'id' => $aluno->getId(),
            'nome' => $aluno->getNome(),
        ];
    }
    public static function collection(array $alunos): array
    {
        return array_map(fn($aluno) => [
            'id' => $aluno->getId(),
            'nome' => $aluno->getNome(),
        ], $alunos);
    }
}