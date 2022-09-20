<?php

namespace App\Packages\Aluno\Facade;


use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Aluno\Domain\Repository\AlunoRepository;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;

class AlunoFacade
{
    public function __construct(AlunoRepository $alunoRepository)
    {
        $this->alunoRepository = $alunoRepository;
    }

    public function create(string $nome)
    {
        $aluno = new Aluno(Str::uuid(), $nome);
        EntityManager::persist($aluno);
        EntityManager::flush();
        return $aluno;
    }
}