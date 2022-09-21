<?php

namespace App\Http\Controllers;


use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Aluno\Domain\Repository\AlunoRepository;
use App\Packages\Aluno\Facade\AlunoFacade;
use App\Packages\Prova\Domain\Model\Prova;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function __construct(private AlunoRepository $alunoRepository, private AlunoFacade $alunoFacade)
    {
    }

    public function index()
    {
        $alunos = $this->alunoRepository->findAll();
        $response = array_map(fn($aluno) => [
            'id' => $aluno->getId(),
            'nome' => $aluno->getNome(),
        ], $alunos);
        return response()->json(['data' => $response], HttpStatusConstants::OK);
    }

    public function store(Request $request)
    {
        try {
            $aluno = $this->alunoFacade->create($request->get('nome'));
            $this->alunoRepository->flush();
            return response()->json(
                [
                    'data' => [
                        'id' => $aluno->getId(),
                        'nome' => $aluno->getNome(),
                    ]
                ], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function listProvas(Aluno $aluno)
    {
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
        ], $aluno->getProvas()->toArray());
        return response()->json([
            'data' => $response
        ], HttpStatusConstants::OK);
    }
}