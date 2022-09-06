<?php

namespace App\Packages\Temas\Domain\Model;

use App\Packages\Questoes\Domain\Model\Questoes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="temas")
 */
class Temas
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Packages\Questoes\Domain\Model\Questoes",
     *     fetch="EXTRA_LAZY",
     *     mappedBy="temas"
     * )
     */
    private ?Questoes $questoes = null;

    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="uuid", unique=true)
         * @ORM\GeneratedValue(strategy="CUSTOM")
         * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
         */
        private string $id,
        /** @ORM\Column(type="string") */
        private string $name,
        /** @ORM\Column(type="string") */
        private string $slugname,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlugname(): string
    {
        return $this->slugname;
    }
}