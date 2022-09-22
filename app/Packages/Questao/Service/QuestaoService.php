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
        $alternativasCorretas = 0;
        foreach ($alternativas as $alternativa) {
            if($alternativa['isCorreta']) {
                $alternativasCorretas++;
            }
            $questao->addAlternativa($alternativa['resposta'], $alternativa['isCorreta']);
        }
        $this->throwExceptionSeNaoExistirSomenteUmaAlternativaCorreta($alternativasCorretas);
    }

    private function throwExceptionSeJaExistirAlternativas(Questao $questao): void
    {
        if ($questao->getRespostas()->count() > 0) {
            throw new \Exception('A questão já possui alternativas', 1663798294);
        }
    }

    private function throwExceptionSeNaoExistirSomenteUmaAlternativaCorreta(int $alternativasCorretas): void
    {
        if ($alternativasCorretas === 0) {
            throw new \Exception('A questão deve ter uma alternativa correta', 1663702752);
        }
        if ($alternativasCorretas > 1) {
            throw new \Exception('A questão só pode ter uma alternativa correta', 1663797428);
        }
    }
}