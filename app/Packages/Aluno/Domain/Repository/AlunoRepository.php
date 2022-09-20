<?php

namespace App\Packages\Aluno\Domain\Repository;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Base\Domain\Repository\Repository;

class AlunoRepository extends Repository
{
    protected string $entityName = Aluno::class;
}
