<?php

namespace App\Packages\Prova\Facade;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Dto\RespostasProvaDto;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Domain\Repository\ProvaRepository;
use App\Packages\Prova\Service\ProvaService;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;

class ProvaFacade
{
    public function __construct(private ProvaRepository $provaRepository, private ProvaService $provaService)
    {
    }

    public function create(Aluno $aluno, string $tema): Prova
    {
        $prova = $this->provaService->create($aluno, $tema);
        $this->provaRepository->add($prova);
        return $prova;
    }

    public function responder(Prova $prova, array $respostas): Prova
    {
        $this->provaService->responder($prova, $respostas);
        $this->provaRepository->update($prova);
        return $prova;
    }

    public function getAll()
    {
        return $this->provaRepository->findAll();
    }
}