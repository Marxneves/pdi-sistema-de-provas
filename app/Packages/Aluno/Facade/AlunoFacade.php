<?php

namespace App\Packages\Aluno\Facade;


use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Aluno\Domain\Repository\AlunoRepository;
use Illuminate\Support\Str;

class AlunoFacade
{
    public function __construct(private AlunoRepository $alunoRepository)
    {
    }

    public function create(string $nome)
    {
        $aluno = new Aluno(Str::uuid(), $nome);
        $this->alunoRepository->add($aluno);
        return $aluno;
    }
}