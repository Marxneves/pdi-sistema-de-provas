<?php

namespace App\Packages\Questao\Facade;


use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Domain\Model\Tema;
use Illuminate\Support\Str;

class QuestaoFacade
{
    public function __construct(private TemaRepository $temaRepository, private QuestaoRepository $questaoRepository)
    {
    }

    public function create(string $temaSlugname, string $pergunta): Questao
    {
        $tema = $this->temaRepository->findOneBySlugname($temaSlugname);
        $this->throwExceptionSeTemaNaoExistir($tema);
        $questao = new Questao(Str::uuid(), $tema, $pergunta);
        $this->questaoRepository->add($questao);
        return $questao;
    }

    public function addAlternativa(Questao $questao, string $resposta, bool $isCorreta): Questao
    {
        $questao->addAlternativa($resposta, $isCorreta);
        $this->questaoRepository->update($questao);
        return $questao;
    }


    private function throwExceptionSeTemaNaoExistir(?Tema $tema): void
    {
        if (!$tema instanceof Tema) {
            throw new \Exception('O tema da questão não existe', 1663702752);
        }
    }
}