<?php

namespace App\Packages\Tema\Tests\Unit\Facade;


use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Facade\TemaFacade;
use Tests\TestCase;

class TemaFacadeTest extends TestCase
{

    public function testIfCreateTema()
    {
        $temaRepository = $this->createMock(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname');
        $temaRepository->expects($this->once())->method('add');
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $tema = app(TemaFacade::class)->create('Novo Tema','novo_tema');
        self::assertInstanceOf(Tema::class, $tema);
        self::assertSame('Novo Tema', $tema->getNome());
        self::assertSame('novo_tema', $tema->getSlugname());
    }

    public function testIfThrowExceptionWhenTemaExists()
    {
        $temaMock = $this->createMock(Tema::class);
        $temaRepository = $this->createMock(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname')->willReturn($temaMock);
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('O tema já existe.');
        $this->expectExceptionCode(1663702757);

        app(TemaFacade::class)->create('Novo Tema','tema');
    }
}