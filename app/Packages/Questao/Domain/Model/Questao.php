<?php

namespace App\Packages\Questao\Domain\Model;

use App\Packages\Tema\Domain\Model\Tema;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Illuminate\Support\Str;

/**
 * @ORM\Entity
 * @ORM\Table(name="questoes")
 */
class Questao
{
    use TimestampableEntity;

    /**
     * @ORM\OneToMany(targetEntity="App\Packages\Questao\Domain\Model\Alternativa", fetch="EXTRA_LAZY", mappedBy="questao", cascade={"all"})
     */
    private ?Collection $alternativas;

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
         *     targetEntity="App\Packages\Tema\Domain\Model\Tema",
         *     inversedBy="questoes"
         * )
         */
        private Tema   $tema,

        /** @ORM\Column(type="string") */
        private string $pergunta,
    )
    {
        $this->alternativas = new ArrayCollection;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTema(): Tema
    {
        return $this->tema;
    }

    public function getPergunta(): string
    {
        return $this->pergunta;
    }

    public function getRespostas(): ?Collection
    {
        return $this->alternativas;
    }

    public function getAlternativas(): ?Collection
    {
        return $this->alternativas;
    }

    public function addAlternativa($resposta, $isCorreta): void
    {
        $this->alternativas->add(new Alternativa(Str::uuid(), $this, $resposta, $isCorreta));
    }
}