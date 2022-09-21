<?php

namespace App\Packages\Prova\Tests\Unit\Facade;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Domain\Repository\ProvaRepository;
use App\Packages\Prova\Facade\ProvaFacade;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Tests\TestCase;

class ProvaFacadeTest extends TestCase
{
    public function testIfCreateProva()
    {
        $alunoMock = $this->createMock(Aluno::class);
        $temaMock = $this->createMock(Tema::class);
        $temaRepositoryMock = $this->createMock(TemaRepository::class);
        $questaoRepositoryMock = $this->createMock(QuestaoRepository::class);
        $provaRepositoryMock = $this->createMock(ProvaRepository::class);

        $temaRepositoryMock->method('findOneBySlugname')->willReturn($temaMock);
        $questaoRepositoryMock->method('findRandomByTemaAndLimit')->willReturn([]);
        $provaRepositoryMock->expects($this->once())->method('add');

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepositoryMock);
        $this->app->bind(ProvaRepository::class, fn() => $provaRepositoryMock);

        /** @var ProvaFacade $provaFacade */
        $provaFacade = app(ProvaFacade::class);
        $prova = $provaFacade->create($alunoMock, 'tema-teste');
        $this->assertInstanceOf(Prova::class, $prova);
    }

    public function testIfRespondeProva()
    {
        $provaMock = $this->createMock(Prova::class);
        $provaRepositoryMock = $this->createMock(ProvaRepository::class);

        $provaMock->method('getStatus')->willReturn(Prova::ABERTA);
        $provaRepositoryMock->expects($this->once())->method('update');

        $this->app->bind(ProvaRepository::class, fn() => $provaRepositoryMock);

        /** @var ProvaFacade $provaFacade */
        $provaFacade = app(ProvaFacade::class);
        $prova = $provaFacade->responder($provaMock, []);
        $this->assertInstanceOf(Prova::class, $prova);
    }

    public function testIfThrowExceptionProvaConcluida()
    {
        $provaMock = $this->createMock(Prova::class);
        $provaMock->method('getStatus')->willReturn(Prova::CONCLUIDA);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Prova já concluída.');
        $this->expectExceptionCode(1663702741);

        /** @var ProvaFacade $provaFacade */
        $provaFacade = app(ProvaFacade::class);
        $provaFacade->responder($provaMock, []);
    }
}