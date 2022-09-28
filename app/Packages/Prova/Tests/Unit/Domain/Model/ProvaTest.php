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
        $alunoMock = $this->createMock(Aluno::class);
        $temaMock = $this->createMock(Tema::class);
        $prova = new Prova(Str::uuid(), $alunoMock, $temaMock);
        $prova->setCreatedAt(now());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Prova enviada fora do tempo limite.');
        $this->expectExceptionCode(1663470013);

        Carbon::setTestNow(Carbon::now()->addHour());
        $prova->responder(collect());
    }

    public function testIfCalculaNota()
    {
        $alunoMock = $this->createMock(Aluno::class);
        $temaMock = $this->createMock(Tema::class);
        $respostaProvaDtoMock = $this->createMock(RespostasProvaDto::class);

        $prova = new Prova(Str::uuid(), $alunoMock, $temaMock);
        $questao = new Questao(Str::uuid(), $temaMock, 'Pergunta da vez');

        $questaoCollection = [$questao];
        $prova->setQuestoes($questaoCollection);

        $respostaProvaDtoMock->method('getQuestaoId')->willReturn($prova->getQuestoes()->first()->getId());
        $respostaProvaDtoMock->method('getRespostaAluno')->willReturn('');


        $respostaCollection = collect();
        $respostaCollection->add($respostaProvaDtoMock);
        $prova->responder($respostaCollection);

        $this->assertEquals(10, $prova->getNota());
    }
}