<?php

namespace App\Http\Controllers;


use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Aluno\Domain\Repository\AlunoRepository;
use App\Packages\Aluno\Facade\AlunoFacade;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function __construct(AlunoRepository $alunoRepository, AlunoFacade $alunoFacade)
    {
        $this->alunoRepository = $alunoRepository;
        $this->alunoFacade = $alunoFacade;
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
                ['data' =>
                    [
                        'id' => $aluno->getId(),
                        'nome' => $aluno->getNome(),
                    ]
                ], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], HttpStatusConstants::BAD_REQUEST);
        }
    }
}