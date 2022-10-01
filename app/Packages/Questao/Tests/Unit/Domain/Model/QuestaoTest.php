<?php

namespace App\Packages\Questao\Tests\Unit\Domain\Model;

use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use Illuminate\Support\Str;
use Tests\TestCase;

class QuestaoTest extends TestCase
{
    public function exceptionProvider(): array
    {
        return [
            'Nenhuma Alternativa correta' => [
                'alternativas' => [
                    ['resposta' => 'Resposta 1', 'isCorreta' => false],
                    ['resposta' => 'Resposta 2', 'isCorreta' => false],
                    ['resposta' => 'Resposta 2', 'isCorreta' => false],
                    ['resposta' => 'Resposta 2', 'isCorreta' => false],
                ],
                'exceptionMessage' => 'A questão deve ter uma alternativa correta',
                'exceptionCode' => 1663702752
            ],
            'Mais que uma alternativa correta' => [
                'alternativas' => [
                    ['resposta' => 'Resposta 1', 'isCorreta' => true],
                    ['resposta' => 'Resposta 2', 'isCorreta' => true],
                    ['resposta' => 'Resposta 2', 'isCorreta' => false],
                    ['resposta' => 'Resposta 2', 'isCorreta' => false],
                ],
                'exceptionMessage' => 'A questão só pode ter uma alternativa correta',
                'exceptionCode' => 1663797428
            ],
        ];
    }

    /** @dataProvider exceptionProvider */
    public function testIfThrowExceptionQuandoNaoTemSomenteUmaAlternativaCorreta(array $alternativas, string $exceptionMessage, int $exceptionCode)
    {
        $temaMock = $this->createStub(Tema::class);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da questao?');

        $this->expectExceptionObject(new \Exception($exceptionMessage, $exceptionCode));

        $questao->setAlternativas($alternativas);
    }
}