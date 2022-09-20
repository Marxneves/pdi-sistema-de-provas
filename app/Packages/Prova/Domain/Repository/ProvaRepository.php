<?php

namespace App\Packages\Prova\Domain\Repository;

use App\Packages\Base\Domain\Repository\Repository;
use App\Packages\Prova\Domain\Model\Prova;
class ProvaRepository extends Repository
{
    protected string $entityName = Prova::class;
}