<?php

namespace App\Packages\Prova\Tests\Unit\Service;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Service\ProvaService;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Tests\TestCase;

class ProvaServiceTest extends TestCase
{
    public function testIfCreateProva()
    {
        $alunoMock = $this->createMock(Aluno::class);
        $temaMock = $this->createMock(Tema::class);
        $temaRepositoryMock = $this->createMock(TemaRepository::class);
        $questaoRepositoryMock = $this->createMock(QuestaoRepository::class);

        $temaRepositoryMock->method('findOneBySlugname')->willReturn($temaMock);
        $questaoRepositoryMock->method('findRandomByTemaAndLimit')->willReturn([]);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepositoryMock);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $prova = $provaService->create($alunoMock, 'tema-teste');
        $this->assertInstanceOf(Prova::class, $prova);
    }

    public function testIfRespondeProva()
    {
        $provaMock = $this->createMock(Prova::class);

        $provaMock->method('getStatus')->willReturn(Prova::ABERTA);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $prova = $provaService->responder($provaMock, []);
        $this->assertInstanceOf(Prova::class, $prova);
    }

    public function testIfThrowExceptionProvaConcluida()
    {
        $provaMock = $this->createMock(Prova::class);
        $provaMock->method('getStatus')->willReturn(Prova::CONCLUIDA);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Prova já concluída.');
        $this->expectExceptionCode(1663702741);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->responder($provaMock, []);
    }
}