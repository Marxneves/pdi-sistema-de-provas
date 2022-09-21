<?php

namespace App\Packages\Questao\Tests\Unit\Facade;


use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Questao\Facade\QuestaoFacade;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuestaoFacadeTest extends TestCase
{

    public function testIfCreateQuestao()
    {
        $temaMock = $this->createMock(Tema::class);
        $temaRepository = $this->createMock(TemaRepository::class);
        $questaoRepository = $this->createMock(QuestaoRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname')->willReturn($temaMock);

        $questaoRepository->expects($this->once())->method('add');
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepository);

        $questao = app(QuestaoFacade::class)->create('tema_slugname','Pergunta da questao?');
        self::assertInstanceOf(Questao::class, $questao);
        self::assertSame('Pergunta da questao?', $questao->getPergunta());
    }

    public function testIfThrowExceptionWhenTemaNaoExistir()
    {
        $temaRepository = $this->createMock(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname');;
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('O tema da questão não existe');
        $this->expectExceptionCode(1663702752);

        app(QuestaoFacade::class)->create('tema_slugname','Pergunta da questao?');
    }

    public function testIfAddAlternativa()
    {
        $temaMock = $this->createMock(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $questaoRepository = $this->createMock(QuestaoRepository::class);
        $questaoRepository->expects($this->once())->method('update');
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepository);

        $questao = app(QuestaoFacade::class)->addAlternativa($questao,'Resposta da questao', true);
        self::assertSame('Resposta da questao', $questao->getAlternativas()[0]->getAlternativa());
        self::assertTrue($questao->getAlternativas()[0]->isCorreta());
    }
}