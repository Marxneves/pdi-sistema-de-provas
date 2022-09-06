<?php

namespace App\Packages\Questoes\Domain\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="respostas")
 */
class Respostas
{
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
         *     targetEntity="App\Packages\Questoes\Domain\Model\Questoes",
         *     inversedBy="respostas"
         * )
         */
        private Questoes $questao,
        /** @ORM\Column(type="string") */
        private string $resposta,
        /** @ORM\Column(type="boolean") */
        private bool $correta,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getQuestao(): Questoes
    {
        return $this->questao;
    }

    public function getResposta(): string
    {
        return $this->resposta;
    }

    public function isCorreta(): bool
    {
        return $this->correta;
    }

}