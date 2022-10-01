<?php

namespace App\Packages\Questao\Service;


use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;

class QuestaoService
{
    public function __construct(private TemaRepository $temaRepository)
    {
    }

    public function create(string $temaSlugname, string $pergunta): Questao
    {
        $tema = $this->temaRepository->findOneBySlugname($temaSlugname);
        $this->throwExceptionSeTemaNaoExistir($tema);
        $questao = new Questao(Str::uuid(), $tema, $pergunta);
        return $questao;
    }

    private function throwExceptionSeTemaNaoExistir(?Tema $tema): void
    {
        if (!$tema instanceof Tema) {
            throw new \Exception('O tema da questão não existe', 1663702752);
        }
    }

    public function addAlternativas(Questao $questao, array $alternativas): void
    {
        $this->throwExceptionSeJaExistirAlternativas($questao);
        $this->throwExceptionSeNaoTiverQuatroAlternativas($alternativas);
        $questao->setAlternativas($alternativas);
    }

    private function throwExceptionSeJaExistirAlternativas(Questao $questao): void
    {
        if ($questao->getAlternativas()->count() > 0) {
            throw new \Exception('A questão já possui alternativas', 1663798294);
        }
    }

    private function throwExceptionSeNaoTiverQuatroAlternativas(array $alternativas)
    {
        if (count($alternativas) !== 4) {
            throw new \Exception('A questão deve ter quatro alternativas', 1664327303);
        }
    }
}