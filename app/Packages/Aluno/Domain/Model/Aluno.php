<?php

namespace App\Packages\Aluno\Domain\Model;

use App\Packages\Prova\Domain\Model\Prova;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="alunos")
 */
class Aluno
{
    use TimestampableEntity;

    /**
     * @ORM\ManyToMany(targetEntity="App\Packages\Prova\Domain\Model\Prova")
     * @ORM\JoinTable(name="alunos_provas", joinColumns={@ORM\JoinColumn(name="aluno_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="prova_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private Collection $provas;

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
    )
    {
        $this->provas = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getProvas(): Collection
    {
        return $this->provas;
    }

    public function addProva(Prova $prova)
    {
        $this->provas->add($prova);
    }
}