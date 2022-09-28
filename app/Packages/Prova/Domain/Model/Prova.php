<?php

namespace App\Packages\Prova\Domain\Model;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Dto\RespostasProvaDto;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Illuminate\Support\Collection as IlluminateCollection;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;

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
        private string     $id,

        /**
         * @ORM\ManyToOne(
         *     targetEntity="App\Packages\Aluno\Domain\Model\Aluno",
         *     inversedBy="provas"
         * )
         */
        private Aluno      $aluno,

        /**
         * @ORM\ManyToOne(
         *     targetEntity="App\Packages\Tema\Domain\Model\Tema",
         *     inversedBy="provas"
         * )
         */
        private Tema       $tema,

        /** @ORM\Column(type="float", nullable=true) */
        private ?float     $nota = null,

        /** @ORM\Column(type="datetime", nullable=true) */
        private ?\DateTime $submetidaEm = null,

        /** @ORM\Column(type="string", options={"default":"Aberta"}) */
        private ?string    $status = 'Aberta',
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getSubmetidaEm(): ?\DateTime
    {
        return $this->submetidaEm;
    }

    public function getQuestoes(): Collection
    {
        return $this->questoes;
    }

    public function getTema(): Tema
    {
        return $this->tema;
    }

    public function responder(IlluminateCollection $respostas): void
    {
        $this->submetidaEm = now();
        $this->status = self::CONCLUIDA;

        $this->throwExceptionIfProvaForaDoPrazo();

        $questoesCorretas = 0;
        $questoesCorretas = $this->verificaESetaRespostasCorretasDoAluno($respostas, $questoesCorretas);

        $this->calculaNotaProva($questoesCorretas);
    }

    private function throwExceptionIfProvaForaDoPrazo(): void
    {
        if ($this->submetidaEm->diff($this->createdAt)->h >= 1) {
            $this->nota = 0;
            throw new \Exception('Prova enviada fora do tempo limite.', 1663470013);
        }
    }

    private function verificaESetaRespostasCorretasDoAluno(IlluminateCollection $respostas, int $questoesCorretas): int
    {
        $questoesProva = $this->questoes;
        foreach ($questoesProva as $questaoProva) {
            /** @var QuestaoProva $questaoProva */
            foreach ($respostas as $resposta) {
                if ($questaoProva->getId() === $resposta->getQuestaoId()) {
                    $questaoProva->setRespostaAluno($resposta->getRespostaAluno());
                    $this->somaSeRespostaAlunoForCorreta($questaoProva, $resposta, $questoesCorretas);
                }
            }
        }
        return $questoesCorretas;
    }

    public function somaSeRespostaAlunoForCorreta(QuestaoProva $questaoProva, RespostasProvaDto $resposta, int &$questoesCorretas): void
    {
        if ($questaoProva->getRespostaCorreta() === $resposta->getRespostaAluno()) {
            $questoesCorretas += 1;
        }
    }

    private function calculaNotaProva(int $questoesCorretas): void
    {
        $this->nota = round($questoesCorretas * (self::NOTA_MAXIMA / $this->questoes->count()), 1);
    }
}