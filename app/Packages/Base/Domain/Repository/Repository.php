<?php


namespace App\Packages\Base\Domain\Repository;


use Doctrine\ORM\QueryBuilder;
use LaravelDoctrine\ORM\Facades\EntityManager;

class Repository extends AbstractRepository
{
    public function add(object $entity)
    {
        EntityManager::persist($entity);
    }

    public function update(object $entity)
    {
        return EntityManager::merge($entity);
    }

    public function remove(object $entity)
    {
        EntityManager::remove($entity);
    }

    public function flush()
    {
        EntityManager::flush();
    }

    public function flushObject(object $object)
    {
        EntityManager::flush($object);
    }

    public function getPaginateResult(QueryBuilder $builder, ?int $page = 1, ?int $limit = 30): mixed
    {
        return $builder
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
