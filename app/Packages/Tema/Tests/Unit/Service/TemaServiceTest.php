<?php

namespace App\Packages\Tema\Tests\Unit\Facade;


use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Service\TemaService;
use Tests\TestCase;

class TemaServiceTest extends TestCase
{

    public function testIfCreateTema()
    {
        $temaRepository = $this->createStub(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname');
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $tema = app(TemaService::class)->create('Novo Tema','novo_tema');
        self::assertInstanceOf(Tema::class, $tema);
        self::assertSame('Novo Tema', $tema->getNome());
        self::assertSame('novo_tema', $tema->getSlugname());
    }

    public function testIfThrowExceptionWhenTemaExists()
    {
        $temaMock = $this->createStub(Tema::class);
        $temaRepository = $this->createStub(TemaRepository::class);
        $temaRepository->expects($this->once())->method('findOneBySlugname')->willReturn($temaMock);
        $this->app->bind(TemaRepository::class, fn() => $temaRepository);

        $this->expectExceptionObject(new \Exception('O tema já existe.', 1663702757));

        app(TemaService::class)->create('Novo Tema','tema');
    }
}