<?php

namespace App\Http\Controllers;

use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Domain\Repository\ProvaRepository;
use App\Packages\Prova\Facade\ProvaFacade;
use Illuminate\Http\Request;

class ProvaController extends Controller
{
    public function __construct(private ProvaRepository $provaRepository, private ProvaFacade $provaFacade)
    {
    }

    public function index()
    {
        $provas = $this->provaRepository->findAll();
        $response = array_map(fn($prova) => [
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
        return response()->json(['data' => $response], HttpStatusConstants::OK);
    }

    public function show(Prova $prova)
    {
        return response()->json([
            'data' => [
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
            ]
        ], HttpStatusConstants::OK);
    }

    public function store(Aluno $aluno, Request $request)
    {
        try {
            $prova = $this->provaFacade->create($aluno, $request->get('tema'));
            $this->provaRepository->flush();
            return response()->json(
                [
                    'data' => [
                        'id' => $prova->getId(),
                        'tema' => $prova->getTema()->getNome(),
                        'questoes' => array_map(fn($questao) => [
                            'id' => $questao->getId(),
                            'pergunta' => $questao->getPergunta(),
                            'alternativas' =>
                                array_map(fn($alternativa) => [
                                    'id' => $alternativa->getId(),
                                    'alternativa' => $alternativa->getAlternativa(),
                                ], $questao->getAlternativas()->toArray())
                        ], $prova->getQuestoes()->toArray())
                    ]
                ], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function enviarRepostas(Prova $prova, Request $request)
    {
        try {
            $prova = $this->provaFacade->responder($prova, $request->get('respostas'));
            $this->provaRepository->flush();
            return response()->json(
                [
                    'data' => [
                        'id' => $prova->getId(),
                        'questoes' => array_map(fn($questao) => [
                            'id' => $questao->getId(),
                            'pergunta' => $questao->getPergunta(),
                            'respostaCorreta' => $questao->getRespostaCorreta(),
                            'respostaAluno' => $questao->getRespostaAluno(),
                        ], $prova->getQuestoes()->toArray()),
                        'nota' => $prova->getNota(),
                        'notaMaxima' => Prova::NOTA_MAXIMA
                    ]
                ], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(['error' => true, 'message' => $exception->getMessage(), 'code' => $exception->getCode()], HttpStatusConstants::BAD_REQUEST);
        }
    }
}