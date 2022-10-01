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
        $temaMock = $this->createStub(Tema::class);
        $temaRepository = $this->createStub(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname')->willReturn($temaMock);
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $questao = app(QuestaoService::class)->create('tema_slugname','Pergunta da questao?');
        self::assertInstanceOf(Questao::class, $questao);
        self::assertSame('Pergunta da questao?', $questao->getPergunta());
    }

    public function testIfThrowExceptionWhenTemaNaoExistir()
    {
        $temaRepository = $this->createStub(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname');;
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $this->expectExceptionObject(new \Exception('O tema da questão não existe', 1663702752));

        app(QuestaoService::class)->create('tema_slugname','Pergunta da questao?');
    }

    public function testIfAddAlternativa()
    {
        $temaMock = $this->createStub(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
            ['resposta' => 'Resposta 3', 'isCorreta' => false],
            ['resposta' => 'Resposta 4', 'isCorreta' => false],
        ];
        app(QuestaoService::class)->addAlternativas($questao, $alternativas);
        self::assertSame('Resposta 1', $questao->getAlternativas()[0]->getAlternativa());
        self::assertTrue($questao->getAlternativas()[0]->isCorreta());
    }

    public function testIfThrowExceptionSeNaoTiverQuatroAlternativas()
    {
        $temaMock = $this->createStub(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
            ['resposta' => 'Resposta 3', 'isCorreta' => false],
        ];

        $this->expectExceptionObject(new \Exception('A questão deve ter quatro alternativas', 1664327303));

        app(QuestaoService::class)->addAlternativas($questao, $alternativas);
    }

    public function testIfThrowExceptionIfJaExistiremAlternativas()
    {
        $temaMock = $this->createStub(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $alternativas = [
            ['resposta' => 'Resposta 1', 'isCorreta' => true],
            ['resposta' => 'Resposta 2', 'isCorreta' => false],
            ['resposta' => 'Resposta 3', 'isCorreta' => false],
            ['resposta' => 'Resposta 4', 'isCorreta' => false],
        ];
        app(QuestaoService::class)->addAlternativas($questao, $alternativas);

        $this->expectExceptionObject(new \Exception('A questão já possui alternativas', 1663798294));

        app(QuestaoService::class)->addAlternativas($questao, $alternativas);
    }
}