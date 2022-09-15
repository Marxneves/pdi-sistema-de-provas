<?php

namespace App\Packages\Prova\Domain\Model;

use App\Packages\Aluno\Domain\Model\Aluno;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
         * @ORM\ManyToMany(targetEntity="App\Packages\Questao\Domain\Model\Questao")
         * @ORM\JoinTable(name="prova_questoes", joinColumns={@ORM\JoinColumn(name="prova_id", referencedColumnName="id")},
         *      inverseJoinColumns={@ORM\JoinColumn(name="questao_id", referencedColumnName="id", unique=true)}
         *      )
         */
        private Collection $questoes,

        /** @ORM\Column(type="float", nullable=true) */
        private ?float $nota,

        /** @ORM\Column(type="datetime", nullable=true) */
        private ?\DateTime $submetidaEm,

        /** @ORM\Column(type="jsonb", nullable=true) */
        private ?array $respostasAluno,

        /** @ORM\Column(type="string", options={"default":"Aberta"}) */
        private ?string $status='Aberta',
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNota(): ?float
    {
        return $this->nota;
    }

    public function setNota(float $nota): void
    {
        $this->nota = $nota;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getSubmetidaEm(): ?\DateTime
    {
        return $this->submetidaEm;
    }

    public function setSubmetidaEm(\DateTime $submetidaEm): void
    {
        $this->submetidaEm = $submetidaEm;
    }

    public function getRespostasAluno(): ?array
    {
        return $this->respostasAluno;
    }

    public function setRespostasAluno(?array $respostasAluno): void
    {
        $this->respostasAluno = $respostasAluno;
    }

    public function getQuestoes(): Collection
    {
        return $this->questoes;
    }

    public function responder(array $respostas): void
    {
        $this->respostasAluno = $respostas;
        $this->submetidaEm = new \DateTime();
        $this->status = 'Concluida';
    }
}