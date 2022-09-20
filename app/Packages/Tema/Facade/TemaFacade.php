<?php

namespace App\Packages\Tema\Facade;


use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Domain\Model\Tema;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;

class TemaFacade
{
    public function __construct(TemaRepository $temaRepository)
    {
        $this->temaRepository = $temaRepository;
    }

    public function create(string $name, string $slugname)
    {
        $tema = $this->temaRepository->findOneBySlugname($slugname);
        if($tema instanceof Tema) {
            throw new \Exception('o tema já existe.', 1663702757);
        }
        $tema = new Tema(Str::uuid(), $name, $slugname);
        EntityManager::persist($tema);
        EntityManager::flush();
        return $tema;
    }
}