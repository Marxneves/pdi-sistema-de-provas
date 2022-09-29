<?php

namespace App\Packages\Tema\Facade;

use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Service\TemaService;

class TemaFacade
{
    public function __construct(private TemaRepository $temaRepository, private TemaService $temaService)
    {
    }

    public function create(string $nome, string $slugname)
    {
        $tema = $this->temaService->create($nome, $slugname);
        $this->temaRepository->add($tema);
        return $tema;
    }

    public function getAll(): array
    {
        return $this->temaRepository->findAll();
    }
}