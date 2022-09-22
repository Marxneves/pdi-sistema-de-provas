<?php

namespace App\Http\Controllers;

use App\Http\Utilities\ErrorResponse;
use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Domain\Repository\ProvaRepository;
use App\Packages\Prova\Facade\ProvaFacade;
use App\Packages\Prova\Response\ProvaResponse;
use Illuminate\Http\Request;

class ProvaController extends Controller
{
    public function __construct(private ProvaRepository $provaRepository, private ProvaFacade $provaFacade)
    {
    }

    public function index()
    {
        try {
            $provas = $this->provaFacade->getAll();
            return response()->json(['data' => ProvaResponse::collection($provas)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function show(Prova $prova)
    {
        return response()->json(['data' => ProvaResponse::item($prova)], HttpStatusConstants::OK);
    }

    public function store(Aluno $aluno, Request $request)
    {
        try {
            $prova = $this->provaFacade->create($aluno, $request->get('tema'));
            $this->provaRepository->flush();
            return response()->json(['data' => ProvaResponse::item($prova)], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function enviarRepostas(Prova $prova, Request $request)
    {
        try {
            $prova = $this->provaFacade->responder($prova, $request->get('respostas'));
            $this->provaRepository->flush();
            return response()->json(['data' => ProvaResponse::item($prova)], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }
}