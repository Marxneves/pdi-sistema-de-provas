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
                'questaoId'=> '1d56ed50-f65d-4db7-8e3c-b44f0d2e9f40',
                'respostaSelecionaId' => '1e14c412-a73f-4895-88fa-7aa44c5823b7'
            ],
            [
                'questaoId'=> 'f94453f6-e9b9-4931-a8c7-8f1507747ac7',
                'respostaSelecionaId' => 'ee8ca6e1-1860-446c-bb3b-742e565d7c22'
            ],
        ];
        $prova->responder($respostas);
        EntityManager::flush();
    }
}

