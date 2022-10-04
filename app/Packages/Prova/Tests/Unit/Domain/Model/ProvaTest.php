<?php

namespace App\Packages\Prova\Tests\Unit\Domain\Model;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Dto\RespostasProvaDto;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProvaTest extends TestCase
{
    public function testIfThrowExceptionProvaForaDoPrazo()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaMock = $this->createStub(Tema::class);
        $prova = new Prova(Str::uuid(), $alunoMock, $temaMock);
        $prova->setCreatedAt(now());

        $this->expectExceptionObject(new \Exception('Prova enviada fora do tempo limite.',1663470013));

        Carbon::setTestNow(Carbon::now()->addHour()->addSecond());
        $prova->responder(collect());
    }

    public function testIfCalculaNota()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaMock = $this->createStub(Tema::class);
        $respostaProvaDtoMock = $this->createStub(RespostasProvaDto::class);

        $prova = new Prova(Str::uuid(), $alunoMock, $temaMock);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da vez');
        $alternativas = [
            ['alternativa' => 'Resposta Correta', 'isCorreta' => true],
            ['alternativa' => 'Resposta 2', 'isCorreta' => false],
            ['alternativa' => 'Resposta 3', 'isCorreta' => false],
            ['alternativa' => 'Resposta 4', 'isCorreta' => false],
        ];
        $questao->setAlternativas($alternativas);

        $questaoCollection = [$questao];
        $prova->setQuestoes($questaoCollection);

        $respostaProvaDtoMock->method('getQuestaoId')->willReturn($prova->getQuestoes()->first()->getId());
        $respostaProvaDtoMock->method('getRespostaAluno')->willReturn('Resposta Correta');


        $respostaCollection = collect();
        $respostaCollection->add($respostaProvaDtoMock);
        $prova->responder($respostaCollection);

        $this->assertEquals(10, $prova->getNota());
    }
}