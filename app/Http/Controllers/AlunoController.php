<?php

namespace App\Http\Controllers;

use App\Http\Utilities\ErrorResponse;
use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Aluno\Domain\Repository\AlunoRepository;
use App\Packages\Aluno\Facade\AlunoFacade;
use App\Packages\Aluno\Request\AlunoRequest;
use App\Packages\Aluno\Response\AlunoResponse;
use App\Packages\Prova\Response\ProvaResponse;

class AlunoController extends Controller
{
    public function __construct(private AlunoRepository $alunoRepository, private AlunoFacade $alunoFacade)
    {
    }

    public function index()
    {
        try {
            $alunos = $this->alunoFacade->getAll();
            return response()->json(['data' => AlunoResponse::collection($alunos)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function store(AlunoRequest $request)
    {
        try {
            $aluno = $this->alunoFacade->create($request->get('nome'));
            $this->alunoRepository->flush();
            return response()->json(['data' => AlunoResponse::item($aluno)], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function listProvas(Aluno $aluno)
    {
        try {
            $prova = $aluno->getProvas()->toArray();
            return response()->json(['data' => ProvaResponse::collection($prova)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }
}