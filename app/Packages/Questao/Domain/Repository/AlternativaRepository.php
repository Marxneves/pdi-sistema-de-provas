<?php

namespace App\Packages\Questao\Domain\Repository;

use App\Packages\Base\Domain\Repository\Repository;
use App\Packages\Questao\Domain\Model\Alternativa;

class AlternativaRepository extends Repository
{
    protected string $entityName = Alternativa::class;
}