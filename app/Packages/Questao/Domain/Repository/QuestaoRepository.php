<?php

namespace App\Packages\Questao\Domain\Repository;

use App\Packages\Base\Domain\Repository\Repository;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;


class QuestaoRepository extends Repository
{
    protected string $entityName = Questao::class;

    public function findRandomByTemaAndLimit(Tema $tema, int $limit): array
    {
        $query = $this->createQueryBuilder('questions')
            ->select('questions')
            ->where('questions.tema = :tema')
            ->andWhere('questions.alternativas IS NOT EMPTY')
            ->orderBy('RANDOM()')
            ->setMaxResults($limit)
            ->setParameter('tema', $tema)
            ->getQuery();

        return $query->getResult();
    }
}
