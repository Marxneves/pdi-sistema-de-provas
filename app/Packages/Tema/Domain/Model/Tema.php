<?php

namespace App\Packages\Tema\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="temas")
 */
class Tema
{
    use TimestampableEntity;

    /**
     * @ORM\OneToMany(targetEntity="App\Packages\Prova\Domain\Model\Prova", mappedBy="tema")
     */
    private ?Collection $provas;

    /**
     * @ORM\OneToMany(targetEntity="App\Packages\Questao\Domain\Model\Questao", mappedBy="tema")
     */
    private ?Collection $questoes;

    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="uuid", unique=true)
         * @ORM\GeneratedValue(strategy="CUSTOM")
         * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
         */
        private string $id,
        /** @ORM\Column(type="string") */
        private string $nome,
        /** @ORM\Column(type="string") */
        private string $slugname,
    )
    {
        $this->questoes = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getSlugname(): string
    {
        return $this->slugname;
    }
}