<?php

namespace App\Packages\Aluno\Command;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;


class GerarProvaAlunoCommand extends Command
{
    protected $signature = 'gerar:prova {alunoId?}';
    protected $description = 'Gera uma prova para um aluno.';

    public function handle(): void
    {
//        $aluno = EntityManager::getRepository(Aluno::class)->find($this->argument('alunoId'));
        $aluno = new Aluno(Str::uuid(), 'Paulo Primeiro');
        EntityManager::persist($aluno);

        $tema = new Tema(Str::uuid(),'Java', 'java');
        EntityManager::persist($tema);

        $questao = new Questao(Str::uuid(), $tema,'Qual a diferença entre Java e JavaScript?');
        EntityManager::persist($questao);
        $questao->addAlternativa('Java é uma linguagem de programação, JavaScript é uma linguagem de script.', false);
        $questao->addAlternativa('Java é uma linguagem de script, JavaScript é uma linguagem de programação.', false);
        $questao->addAlternativa('Java é uma linguagem de programação, JavaScript é uma linguagem de programação.', true);
        $questao->addAlternativa('Java é uma linguagem de script, JavaScript é uma linguagem de script.', false);
        EntityManager::persist($questao);

        $questao2 = new Questao(Str::uuid(), $tema,'Qual a diferença entre Java e C#?');
        EntityManager::persist($questao2);
        $questao2->addAlternativa('Java é uma linguagem de programação, C# é uma linguagem de script.', false);
        $questao2->addAlternativa('Java é uma linguagem de script, C# é uma linguagem de programação.', false);
        $questao2->addAlternativa('Java é uma linguagem de programação, C# é uma linguagem de programação.', true);

        $questoesCollection = new ArrayCollection();
        $questoesCollection->add($questao);
        $questoesCollection->add($questao2);

        $prova = new Prova(Str::uuid(),$questoesCollection, null, null, null);
        EntityManager::persist($prova);
        $aluno->addProva($prova);
        EntityManager::flush();
    }
}

