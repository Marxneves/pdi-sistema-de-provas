<?php

namespace App\Packages\Prova\Domain\Model;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Illuminate\Support\Str;

/**
 * @ORM\Entity
 * @ORM\Table(name="provas")
 */
class Prova
{
    use TimestampableEntity;

    const NOTA_MAXIMA = 10;
    const CONCLUIDA = 'Concluida';
    const ABERTA = 'Aberta';

    /**
     * @ORM\OneToMany (targetEntity="QuestaoProva", mappedBy="prova", cascade={"all"})
     */
    private Collection $questoes;

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
         *     inversedBy="provas"
         * )
         */
        private Aluno   $aluno,

        /**
         * @ORM\ManyToOne(
         *     targetEntity="App\Packages\Tema\Domain\Model\Tema",
         *     inversedBy="provas"
         * )
         */
        private Tema   $tema,

        /** @ORM\Column(type="float", nullable=true) */
        private ?float $nota = null,

        /** @ORM\Column(type="datetime", nullable=true) */
        private ?\DateTime $submetidaEm = null,

        /** @ORM\Column(type="string", options={"default":"Aberta"}) */
        private ?string $status='Aberta',
    )
    {
        $this->questoes = new ArrayCollection;
    }

    public function setQuestoes(array $questoesCollection)
    {
        foreach ($questoesCollection as $questao) {
            /** @var Questao $questao */
            $questaoProva = new QuestaoProva(Str::uuid(), $this, $questao->getPergunta());
            $questaoProva->setAlternativas($questao->getAlternativas());
            $this->questoes->add($questaoProva);
        }
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

    public function getQuestoes(): Collection
    {
        return $this->questoes;
    }

    public function responder(\Illuminate\Support\Collection $respostas): void
    {
        $this->submetidaEm = now();
        $this->status = self::CONCLUIDA;

        $this->throwExceptionIfProvaForaDoPrazo();

        $questoesCorretas = 0;
        [$questoes, $questoesCorretas] = $this->verificaRespostasCorretasDoAluno($respostas, $questoesCorretas);

        $this->calculaNotaProva($questoesCorretas, $questoes);
    }

    public function getTema(): Tema
    {
        return $this->tema;
    }
    
    private function throwExceptionIfProvaForaDoPrazo(): void
    {
        if ($this->submetidaEm->diff($this->createdAt)->h >= 1) {
            $this->nota = 0;
            throw new \Exception('Prova enviada fora do tempo limite.', 1663470013);
        }
    }

    private function verificaRespostasCorretasDoAluno(\Illuminate\Support\Collection $respostas, int $questoesCorretas): array
    {
        $questoesProva = $this->getQuestoes();
        if ($questoesProva->count() === 0) {
            throw new \Exception('Prova sem questões.', 1663727591);
        }

        foreach ($questoesProva as $questaoProva) {
            /** @var QuestaoProva $questaoProva */
            foreach ($respostas as $resposta) {
                if ($questaoProva->getId() === $resposta->getQuestaoId()) {
                    $questaoProva->setRespostaAluno($resposta->getRespostaAluno());
                    if ($questaoProva->getRespostaCorreta() === $resposta->getRespostaAluno()) {
                        $questoesCorretas += 1;
                    }
                }
            }
        }
        return [$questoesProva, $questoesCorretas];
    }

    private function calculaNotaProva(mixed $questoesCorretas, Collection $questoes): void
    {
        $this->nota = $questoesCorretas * (10 / $questoes->count());
    }
}