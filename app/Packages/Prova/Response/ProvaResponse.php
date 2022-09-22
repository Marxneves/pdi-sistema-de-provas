<?php

namespace App\Packages\Prova\Response;


use App\Packages\Prova\Domain\Model\Prova;

class ProvaResponse
{
    public static function item(Prova $prova): array
    {
        return [
            'id' => $prova->getId(),
            'questoes' => array_map(fn($questao) => [
                'id' => $questao->getId(),
                'pergunta' => $questao->getPergunta(),
                'respostaCorreta' => $questao->getRespostaCorreta(),
                'respostaAluno' => $questao->getRespostaAluno(),
            ], $prova->getQuestoes()->toArray()),
            'status' => $prova->getStatus(),
            'nota' => $prova->getNota(),
            'notaMaxima' => Prova::NOTA_MAXIMA
        ];
    }
    public static function collection(array $provas): array
    {
        return array_map(fn($prova) => [
            'id' => $prova->getId(),
            'questoes' => array_map(fn($questao) => [
                'id' => $questao->getId(),
                'pergunta' => $questao->getPergunta(),
                'respostaCorreta' => $questao->getRespostaCorreta(),
                'respostaAluno' => $questao->getRespostaAluno(),
            ], $prova->getQuestoes()->toArray()),
            'status' => $prova->getStatus(),
            'nota' => $prova->getNota(),
            'notaMaxima' => Prova::NOTA_MAXIMA
        ], $provas);
    }
}