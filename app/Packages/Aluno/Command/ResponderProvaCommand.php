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


class ResponderProvaCommand extends Command
{
    protected $signature = 'responder:prova {provaId?}';
    protected $description = 'responde una prova para um aluno.';

    public function handle(): void
    {
//        $aluno = EntityManager::getRepository(Aluno::class)->findOneBy([]);
        $prova = EntityManager::getRepository(Prova::class)->findOneBy([]);
        $respostas = [
            [
                'questaoId'=> 'c7301a9d-1d8f-4997-8371-4929c3e49720',
                'respostaAluno' => 'Java é uma linguagem de programação, JavaScript é uma linguagem de programação.'
            ],
            [
                'questaoId'=> '7d051961-c518-4845-aaf1-e466c94a4dd0',
                'respostaAluno' => 'Java é uma linguagem de script, C# é uma linguagem de programação.'
            ],
        ];
        $prova->responder($respostas);
        EntityManager::flush();
    }
}

