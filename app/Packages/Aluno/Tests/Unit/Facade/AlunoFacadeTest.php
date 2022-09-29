<?php

namespace App\Packages\Aluno\Tests\Unit\Facade;

use App\Packages\Aluno\Domain\Repository\AlunoRepository;
use App\Packages\Aluno\Facade\AlunoFacade;
use Tests\TestCase;

class AlunoFacadeTest extends TestCase
{
    public function testIfCreateAluno()
    {
        $alunoRepository = $this->createStub(AlunoRepository::class);
        $alunoRepository->expects($this->once())->method('add');
        $this->app->bind(AlunoRepository::class, fn() => $alunoRepository);

        $alunoFacade = app(AlunoFacade::class);
        $aluno = $alunoFacade->create('Aluno Teste');
        $this->assertSame('Aluno Teste', $aluno->getNome());
    }
}