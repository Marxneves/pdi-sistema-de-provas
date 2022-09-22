<?php

namespace App\Packages\Tema\Facade;

use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Domain\Model\Tema;
use Illuminate\Support\Str;

class TemaFacade
{
    public function __construct(private TemaRepository $temaRepository)
    {
    }

    public function create(string $name, string $slugname)
    {
        $tema = $this->temaRepository->findOneBySlugname($slugname);
        $this->throwExceptionSeTemaJaExistir($tema);
        $tema = new Tema(Str::uuid(), $name, $slugname);
        $this->temaRepository->add($tema);
        return $tema;
    }

    public function throwExceptionSeTemaJaExistir(?Tema $tema): void
    {
        if ($tema instanceof Tema) {
            throw new \Exception('O tema já existe.', 1663702757);
        }
    }

    public function getAll(): array
    {
        return $this->temaRepository->findAll();
    }
}