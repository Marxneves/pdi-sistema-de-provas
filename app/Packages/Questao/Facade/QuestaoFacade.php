<?php

namespace App\Packages\Questao\Facade;


use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Domain\Model\Tema;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;

class QuestaoFacade
{
    public function __construct(TemaRepository $temaRepository)
    {
        $this->temaRepository = $temaRepository;
    }

    public function create(string $temaSlugname, string $pergunta): Questao
    {
        $tema = $this->temaRepository->findOneBySlugname($temaSlugname);
        if (!$tema instanceof Tema) {
            throw new \Exception('O tema da questão não existe');
        }

        $questao = new Questao(Str::uuid(), $tema, $pergunta);
        EntityManager::persist($questao);
        EntityManager::flush();
        return $questao;
    }

    public function addAlternativa(Questao $questao, string $resposta, bool $isCorreta): Questao
    {
        $questao->addAlternativa($resposta, $isCorreta);
        return $questao;
    }
}