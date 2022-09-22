<?php

namespace App\Packages\Prova\Service;


use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Dto\RespostasProvaDto;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
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
        $numeroAleatorio = rand(4, 20);
        $questoesCollection = $this->questaoRepository->findRandomByTemaAndLimit($tema, $numeroAleatorio);
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

    private function throwExceptionSeProvaConcluida(Prova $prova): void
    {
        if ($prova->getStatus() === Prova::CONCLUIDA) {
            throw new \Exception('Prova já concluída.', 1663702741);
        }
    }
}