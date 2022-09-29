<?php

namespace App\Http\Controllers;

use App\Http\Utilities\ErrorResponse;
use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Questao\Facade\QuestaoFacade;
use App\Packages\Questao\Request\AlternativaRequest;
use App\Packages\Questao\Request\QuestaoRequest;
use App\Packages\Questao\Response\QuestaoResponse;

class QuestoesController extends Controller
{
    public function __construct(private QuestaoRepository $questaoRepository, private QuestaoFacade $questaoFacade)
    {
    }

    public function index()
    {
        try {
            $questoes = $this->questaoFacade->getAll();
            return response()->json(['data' => QuestaoResponse::collection($questoes)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function show(Questao $questao)
    {
        return response()->json(['data' => QuestaoResponse::item($questao)], HttpStatusConstants::OK);
    }

    public function store(QuestaoRequest $request)
    {
        try {
            $questao = $this->questaoFacade->create($request->get('temaSlugname'), $request->get('pergunta'));
            $this->questaoRepository->flush();
            return response()->json(['data' => QuestaoResponse::item($questao)], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function createAlternativas(Questao $questao, AlternativaRequest $request)
    {
        try {
            $questao = $this->questaoFacade->addAlternativas($questao, $request->get('alternativas'));
            $this->questaoRepository->flush();
            return response()->json(['data' => QuestaoResponse::item($questao)], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }
}