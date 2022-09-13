<?php

namespace App\Packages\Doctrine\Domain\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait Identifiable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }
}
