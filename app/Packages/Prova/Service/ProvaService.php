<?php

namespace App\Packages\Prova\Service;


use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Dto\RespostasProvaDto;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;

class ProvaService
{
    public function __construct(private TemaRepository $temaRepository, private QuestaoRepository $questaoRepository)
    {
    }

    public function create(Aluno $aluno, string $tema): Prova
    {
        $tema = $this->temaRepository->findOneBySlugname($tema);
        $this->throwExceptionSeTemaNaoExistir($tema);
        $numeroAleatorio = rand(4, 20);
        $questoesCollection = $this->questaoRepository->findRandomByTemaAndLimit($tema, $numeroAleatorio);
        $this->throwExceptionSeTemaNaoPossuirQuestoes($questoesCollection);
        $prova = new Prova(Str::uuid(), $aluno, $tema);
        $prova->setQuestoes($questoesCollection);
        return $prova;
    }

    public function responder(Prova $prova, array $respostas): Prova
    {
        $this->throwExceptionSeProvaConcluida($prova);

        $repostasDtoCollection = collect();
        foreach ($respostas as $resposta) {
            $repostasDtoCollection->add(new RespostasProvaDto($resposta['questaoId'], $resposta['respostaAluno']));
        }
        $prova->responder($repostasDtoCollection);
        return $prova;
    }

    private function throwExceptionSeTemaNaoExistir(?Tema $tema): void
    {
        if (!$tema instanceof Tema) {
            throw new \Exception('O tema não existe.', 1663702757);
        }
    }

    private function throwExceptionSeProvaConcluida(Prova $prova): void
    {
        if ($prova->getStatus() === Prova::CONCLUIDA) {
            throw new \Exception('Prova já concluída.', 1663702741);
        }
    }

    private function throwExceptionSeTemaNaoPossuirQuestoes(array $questoesCollection): void
    {
        if (count($questoesCollection) === 0) {
            throw new \Exception('Não possuem questões para esse tema.', 1664391636);
        }
    }
}