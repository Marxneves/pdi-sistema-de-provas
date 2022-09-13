<?php

namespace App\Packages\Prova\Domain\Model;

use App\Packages\Aluno\Domain\Model\Aluno;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="provas")
 */
class Prova
{
    use TimestampableEntity;

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
         *     targetEntity="App\Packages\Aluno\Domain\Model\Aluno",
         *     fetch="EXTRA_LAZY",
         *     inversedBy="provas"
         * )
         */
        private Aluno $aluno,

        /**
         * @ORM\OneToMany (
         *     targetEntity="App\Packages\Questao\Domain\Model\Questao",
         *     fetch="EXTRA_LAZY",
         *     mappedBy="prova",
         * )
         */
        private ArrayCollection $questoes,

        /** @ORM\Column(type="string", options={"default":"Aberta"}) */
        private ?string $status,

        /** @ORM\Column(type="float", nullable=true) */
        private ?float $nota,

        /** @ORM\Column(type="datetime", nullable=true) */
        private ?\DateTime $submetidaEm,

        /** @ORM\Column(type="jsonb", nullable=true) */
        private ?ArrayCollection $respostasAluno,
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAluno(): Aluno
    {
        return $this->aluno;
    }

    public function getNota(): float
    {
        return $this->nota;
    }

    public function setNota(float $nota): void
    {
        $this->nota = $nota;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSubmetidaEm(): \DateTime
    {
        return $this->submetidaEm;
    }

    public function setSubmetidaEm(\DateTime $submetidaEm): void
    {
        $this->submetidaEm = $submetidaEm;
    }

    public function getRespostasAluno(): ?ArrayCollection
    {
        return $this->respostasAluno;
    }

    public function setRespostasAluno(?ArrayCollection $respostasAluno): void
    {
        $this->respostasAluno = $respostasAluno;
    }

    public function getQuestoes(): ArrayCollection
    {
        return $this->questoes;
    }
}