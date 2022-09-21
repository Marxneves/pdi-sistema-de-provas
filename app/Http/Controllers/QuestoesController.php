<?php

namespace App\Http\Controllers;


use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Questao\Facade\QuestaoFacade;
use Illuminate\Http\Request;

class QuestoesController extends Controller
{
    public function __construct(private QuestaoRepository $questaoRepository, private QuestaoFacade $questaoFacade)
    {
    }

    public function index()
    {
        $questoes = $this->questaoRepository->findAll();
        $response = array_map(fn($questao) => [
            'id' => $questao->getId(),
            'tema' => [
                'id' => $questao->getTema()->getId(),
                'name' => $questao->getTema()->getNome(),
                'slugname' => $questao->getTema()->getSlugname(),
            ],
            'pergunta' => $questao->getPergunta(),
            'respostas' => [
                array_map(fn($resposta) => [
                    'id' => $resposta->getId(),
                    'resposta' => $resposta->getAlternativa(),
                    'correta' => $resposta->isCorreta(),
                ], $questao->getRespostas()->toArray())
            ]
        ], $questoes);
        return response()->json(['data' => $response], HttpStatusConstants::OK);
    }

    public function store(Request $request)
    {
        try {
            $questao = $this->questaoFacade->create($request->get('temaSlugname'), $request->get('pergunta'));
            $this->questaoRepository->flush();
            return response()->json(
                ['data' =>
                    [
                        'id' => $questao->getId(),
                        'tema' => [
                            'id' => $questao->getTema()->getId(),
                            'name' => $questao->getTema()->getNome(),
                            'slugname' => $questao->getTema()->getSlugname(),
                        ],
                        'pergunta' => $questao->getPergunta(),
                    ]
                ], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function listRepostas(Questao $questao, Request $request)
    {
        $respostas = $questao->getRespostas();
        $response = array_map(fn($resposta) => [
            'questao' => [
                'id' => $resposta->getQuestao()->getId(),
                'nome' => $resposta->getQuestao()->getPergunta(),
            ],
            'id' => $resposta->getId(),
            'resposta' => $resposta->getAlternativa(),
            'correta' => $resposta->isCorreta(),
        ], $respostas->toArray());
        return response()->json(['data' => $response], HttpStatusConstants::OK);
    }

    public function createResposta(Questao $questao, Request $request)
    {
        try {
            $questao = $this->questaoFacade->addAlternativa($questao, $request->get('resposta'), $request->get('isCorreta'));
            $this->questaoRepository->flush();
            $respostas = $questao->getRespostas();
            return response()->json(
                ['data' =>
                    [
                        'id' => $questao->getId(),
                        'tema' => [
                            'id' => $questao->getTema()->getId(),
                            'name' => $questao->getTema()->getNome(),
                            'slugname' => $questao->getTema()->getSlugname(),
                        ],
                        'pergunta' => $questao->getPergunta(),
                        'respostas' => [
                            array_map(fn($resposta) => [
                                'id' => $resposta->getId(),
                                'resposta' => $resposta->getAlternativa(),
                                'correta' => $resposta->isCorreta(),
                            ], $respostas->toArray())
                        ]
                    ]
                ], HttpStatusConstants::CREATED);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], HttpStatusConstants::BAD_REQUEST);
        }
    }
}