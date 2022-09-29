<?php

namespace App\Packages\Prova\Tests\Unit\Service;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Service\ProvaService;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
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
        $provaMock = $this->createStub(Prova::class);

        $provaMock->method('getStatus')->willReturn(Prova::ABERTA);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $prova = $provaService->responder($provaMock, []);
        $this->assertInstanceOf(Prova::class, $prova);
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
}