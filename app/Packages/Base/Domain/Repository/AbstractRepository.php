<?php


namespace App\Packages\Base\Domain\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

abstract class AbstractRepository extends EntityRepository implements RepositoryInterface
{
    protected string $entityName;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, new ClassMetadata($this->entityName));
    }
}
