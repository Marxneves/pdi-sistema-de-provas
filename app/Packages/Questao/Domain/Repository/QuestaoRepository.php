<?php

namespace App\Packages\Questao\Domain\Repository;

use App\Packages\Base\Domain\Repository\Repository;
use App\Packages\Questao\Domain\Model\Questao;


class QuestaoRepository extends Repository
{
    protected string $entityName = Questao::class;
}
