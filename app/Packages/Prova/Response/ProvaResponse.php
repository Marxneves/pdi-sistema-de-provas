<?php

namespace App\Packages\Prova\Response;


use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Domain\Model\QuestaoProva;

class ProvaResponse
{
    public static function item(Prova $prova): array
    {
        /**@var QuestaoProva $questao  */
        return [
            'id' => $prova->getId(),
            'questoes' => array_map(function ($questao) use ($prova) {
                $questaoData = [
                    'id' => $questao->getId(),
                    'pergunta' => $questao->getPergunta(),
                    'respostaAluno' => $questao->getRespostaAluno(),

                ];
                if ($prova->getStatus() === Prova::ABERTA) {
                    $questaoData['alternativas'] = array_map(function ($alternativa) {
                        return [
                            'id' => $alternativa->getId(),
                            'alternativa' => $alternativa->getAlternativa(),
                        ];
                    }, $questao->getAlternativas()->toArray());
                }
                if ($prova->getStatus() === Prova::CONCLUIDA) {
                    $questaoData['respostaCorreta'] = $questao->getRespostaCorreta();
                }
                return $questaoData;
            }, $prova->getQuestoes()->toArray()),
            'status' => $prova->getStatus(),
            'nota' => $prova->getNota(),
            'notaMaxima' => Prova::NOTA_MAXIMA
        ];
    }
    public static function collection(array $provas): array
    {
        return array_map(fn($prova) => self::item($prova), $provas);
    }
}