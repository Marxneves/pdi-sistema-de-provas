<?php

namespace App\Packages\Prova\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Illuminate\Support\Str;

/**
 * @ORM\Entity
 * @ORM\Table(name="questoes_prova")
 */
class QuestaoProva
{
    use TimestampableEntity;

    /**
     * @ORM\OneToMany(targetEntity="AlternativaProva", fetch="EXTRA_LAZY", mappedBy="questao", cascade={"all"})
     */
    private ?Collection $alternativas;

    /** @ORM\Column(type="string") */
    private ?string $respostaCorreta;

    public function __construct(
        /**
         * @ORM\Id
         * @ORM\Column(type="uuid", unique=true)
         * @ORM\GeneratedValue(strategy="CUSTOM")
         * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
         */
        private string  $id,

        /**
         * @ORM\ManyToOne(
         *     targetEntity="Prova",
         *     inversedBy="questoes"
         * )
         */
        private Prova   $prova,

        /** @ORM\Column(type="string") */
        private string  $pergunta,

        /** @ORM\Column(type="string", nullable=true) */
        private ?string $respostaAluno = null,
    )
    {
        $this->alternativas = new ArrayCollection;
        $this->respostaCorreta = null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPergunta(): string
    {
        return $this->pergunta;
    }

    public function getAlternativas(): ?Collection
    {
        return $this->alternativas;
    }

    public function setAlternativas($alternativas)
    {
        foreach ($alternativas as $alternativa) {
            if ($alternativa->isCorreta()) {
                $this->respostaCorreta = $alternativa->getAlternativa();
            }
            $this->alternativas->add(new AlternativaProva(Str::uuid(), $this, $alternativa->getAlternativa(), $alternativa->isCorreta()));
        }
    }

    public function getRespostaAluno(): ?string
    {
        return $this->respostaAluno;
    }

    public function setRespostaAluno(string $respostaAluno): void
    {
        $this->respostaAluno = $respostaAluno;
    }

    public function getRespostaCorreta(): ?string
    {
        return $this->respostaCorreta;
    }
}