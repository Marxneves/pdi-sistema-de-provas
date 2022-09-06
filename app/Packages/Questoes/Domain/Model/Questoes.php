<?php

namespace App\Packages\Questoes\Domain\Model;

use App\Packages\Temas\Domain\Model\Temas;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="questoes")
 */
class Questoes
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Packages\Questoes\Domain\Model\Respostas",
     *     fetch="EXTRA_LAZY",
     *     mappedBy="questoes"
     * )
     */
    private ?Respostas $respostas = null;

    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="uuid", unique=true)
         * @ORM\GeneratedValue(strategy="CUSTOM")
         * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
         */
        private string $id,
        /**
         * @ORM\ManyToOne(
         *     targetEntity="App\Packages\Temas\Domain\Model\Temas",
         *     inversedBy="questoes"
         * )
         */
        private Temas $tema,
        /** @ORM\Column(type="string") */
        private string $pergunta,
    )
    {
    }
}