<?php

namespace App\Packages\Questao\Tests\Unit\Service;

use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Service\QuestaoService;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuestaoServiceTest extends TestCase
{

    public function testIfCreateQuestao()
    {
        $temaMock = $this->createMock(Tema::class);
        $temaRepository = $this->createMock(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname')->willReturn($temaMock);
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $questao = app(QuestaoService::class)->create('tema_slugname','Pergunta da questao?');
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

        app(QuestaoService::class)->create('tema_slugname','Pergunta da questao?');
    }

    public function testIfAddAlternativa()
    {
        $temaMock = $this->createMock(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
        ];
        app(QuestaoService::class)->addAlternativas($questao, $alternativas);
        self::assertSame('Resposta 1', $questao->getAlternativas()[0]->getAlternativa());
        self::assertTrue($questao->getAlternativas()[0]->isCorreta());
    }

    public function testIfThrowExceptionIfJaExistiremAlternativas()
    {
        $temaMock = $this->createMock(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
        ];
        app(QuestaoService::class)->addAlternativas($questao, $alternativas);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('A questão já possui alternativas');
        $this->expectExceptionCode(1663798294);

        app(QuestaoService::class)->addAlternativas($questao, $alternativas);
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

        app(QuestaoService::class)->addAlternativas($questao, $alternativas);
    }
}