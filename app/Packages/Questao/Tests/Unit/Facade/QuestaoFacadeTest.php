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

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
        ];
        $questao = app(QuestaoFacade::class)->addAlternativas($questao, $alternativas);
        self::assertSame('Resposta 1', $questao->getAlternativas()[0]->getAlternativa());
        self::assertTrue($questao->getAlternativas()[0]->isCorreta());
    }

    public function testIfThrowExceptionIfJaExistiremAlternativas()
    {
        $temaMock = $this->createMock(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $questaoRepository = $this->createMock(QuestaoRepository::class);
        $questaoRepository->expects($this->once())->method('update');
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepository);

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
        ];
        app(QuestaoFacade::class)->addAlternativas($questao, $alternativas);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('A questão já possui alternativas');
        $this->expectExceptionCode(1663798294);

        app(QuestaoFacade::class)->addAlternativas($questao, $alternativas);
    }

    public function exceptionProvider(): array
    {
        return [
            'Nenhuma Alternativa correta' => [
                'alternativas' => [
                    ['resposta' => 'Resposta 1', 'isCorreta' => false],
                    ['resposta' => 'Resposta 2', 'isCorreta' => false],
                ],
                'exceptionMessage' => 'A questão deve ter uma alternativa correta',
                'exceptionCode' => 1663702752
            ],
            'Mais que uma alternativa correta' => [
                'alternativas' => [
                    ['resposta' => 'Resposta 1', 'isCorreta' => true],
                    ['resposta' => 'Resposta 2', 'isCorreta' => true],
                ],
                'exceptionMessage' => 'A questão só pode ter uma alternativa correta',
                'exceptionCode' => 1663797428
            ],
        ];
    }

    /** @dataProvider exceptionProvider */
    public function testIfThrowExceptionQuandoNaoTemSomenteUmaAlternativaCorreta(array $alternativas, string $exceptionMessage, int $exceptionCode)
    {
        $temaMock = $this->createMock(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($exceptionMessage);
        $this->expectExceptionCode($exceptionCode);

        app(QuestaoFacade::class)->addAlternativas($questao, $alternativas);
    }
}