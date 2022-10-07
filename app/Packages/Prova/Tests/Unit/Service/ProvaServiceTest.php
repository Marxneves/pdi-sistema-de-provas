<?php

namespace App\Packages\Prova\Tests\Unit\Service;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Factory\ProvaFactory;
use App\Packages\Prova\Service\ProvaService;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProvaServiceTest extends TestCase
{
    public function testIfCreateProva()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaMock = $this->createStub(Tema::class);
        $questaoMock = $this->createStub(Questao::class);
        $temaRepositoryMock = $this->createStub(TemaRepository::class);
        $questaoRepositoryMock = $this->createStub(QuestaoRepository::class);

        $temaRepositoryMock->method('findOneBySlugname')->willReturn($temaMock);
        $questaoRepositoryMock->method('findRandomByTemaAndLimit')->willReturn([$questaoMock]);
        $questaoMock->method('getAlternativas')->willReturn([]);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepositoryMock);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $prova = $provaService->create($alunoMock, 'tema-teste');
        $this->assertInstanceOf(Prova::class, $prova);
    }

    public function testIfThrowExceptionQuandoTemaNaoExistir()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaRepositoryMock = $this->createStub(TemaRepository::class);
        $temaRepositoryMock->method('findOneBySlugname')->willReturn(null);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);

        $this->expectExceptionObject(new \Exception('O tema não existe.', 1663702757));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->create($alunoMock, 'tema-teste');
    }

    public function testIfThrowExceptionQuandoNaoExisteQuestoesDeUmTema()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaMock = $this->createStub(Tema::class);
        $temaRepositoryMock = $this->createStub(TemaRepository::class);
        $questaoRepositoryMock = $this->createStub(QuestaoRepository::class);

        $temaRepositoryMock->method('findOneBySlugname')->willReturn($temaMock);
        $questaoRepositoryMock->method('findRandomByTemaAndLimit')->willReturn([]);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepositoryMock);

        $this->expectExceptionObject(new \Exception('Não possuem questões para esse tema.', 1664391636));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->create($alunoMock, 'tema-teste');
    }

    public function testIfRespondeProva()
    {
        $prova = $this->createProvaForTeste();
        $respostas = $this->getRespostasAlunoForTest($prova);
        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaCorreta = $provaService->responder($prova, $respostas);

        $this->assertSame(Prova::NOTA_MAXIMA, $provaCorreta->getNota());
        $this->assertSame(Prova::CONCLUIDA, $provaCorreta->getStatus());
    }

    public function testIfThrowExceptionProvaConcluida()
    {
        $provaMock = $this->createStub(Prova::class);
        $provaMock->method('getStatus')->willReturn(Prova::CONCLUIDA);

        $this->expectExceptionObject(new \Exception('Prova já concluída.', 1663702741));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->responder($provaMock, []);
    }

    public function testIfThrowExceptionQuandoQuantidadeRespostasDiferenteQuantidadePerguntas()
    {
        $prova = $this->createProvaForTeste();
        $respostas = [];

        $this->expectExceptionObject(new \Exception('Quantidade de respostas diferente da quantidade de perguntas.', 1664697689));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->responder($prova, $respostas);
    }

    private function createProvaForTeste(): Prova
    {
        $temaStub = $this->createStub(Tema::class);
        $alunoStub = $this->createStub(Aluno::class);

        $prova = new Prova(Str::uuid(), $alunoStub, $temaStub);
        $questaoUm = new Questao(Str::uuid(), $temaStub, 'Qual a melhor linguagem de programação?');
        $questaoUm->setAlternativas([
            ['alternativa' => 'PHP', 'isCorreta' => true],
            ['alternativa' => 'Java', 'isCorreta' => false],
            ['alternativa' => 'C#', 'isCorreta' => false],
            ['alternativa' => 'Python', 'isCorreta' => false],
        ]);
        $prova->setQuestoes([$questaoUm]);
        return $prova;
    }

    private function getRespostasAlunoForTest(Prova $prova): array
    {
        return [
            [
                'questaoId' => $prova->getQuestoes()->first()->getId(),
                'respostaAluno' => $prova->getQuestoes()->first()->getRespostaCorreta(),
            ],
        ];
    }
}