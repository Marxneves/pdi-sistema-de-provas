<?php

namespace App\Packages\Tema\Domain\Repository;

use App\Packages\Base\Domain\Repository\Repository;
use App\Packages\Tema\Domain\Model\Tema;


class TemaRepository extends Repository
{
    protected string $entityName = Tema::class;

    public function findOneBySlugname(string $slugname): ?Tema
    {
        return $this->findOneBy(['slugname' => $slugname]);
    }
}
