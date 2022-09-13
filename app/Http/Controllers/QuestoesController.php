<?php

namespace App\Http\Controllers;


use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Questao\Facade\QuestaoFacade;
use Illuminate\Http\Request;
use LaravelDoctrine\ORM\Facades\EntityManager;

class QuestoesController extends Controller
{
    public function __construct(QuestaoRepository $questaoRepository, QuestaoFacade $questaoFacade)
    {
        $this->questaoRepository = $questaoRepository;
        $this->questaoFacade = $questaoFacade;
    }

    public function index()
    {
        $questoes = $this->questaoRepository->findAll();
        $response = array_map(fn($questao) => [
            'id' => $questao->getId(),
            'tema' => [
                'id' => $questao->getTema()->getId(),
                'name' => $questao->getTema()->getName(),
                'slugname' => $questao->getTema()->getSlugname(),
            ],
            'pergunta' => $questao->getPergunta(),
            'respostas' => [
                array_map(fn($resposta) => [
                    'id' => $resposta->getId(),
                    'resposta' => $resposta->getResposta(),
                    'correta' => $resposta->isCorreta(),
                ], $questao->getRespostas()->toArray())
            ]
        ], $questoes);
        return response()->json(['data' => $response]);
    }

    public function store(Request $request)
    {
        try {
            $questao = $this->questaoFacade->create($request->get('temaSlugname'), $request->get('pergunta'));
            return response()->json(
                ['data' =>
                    [
                        'id' => $questao->getId(),
                        'tema' => [
                            'id' => $questao->getTema()->getId(),
                            'name' => $questao->getTema()->getName(),
                            'slugname' => $questao->getTema()->getSlugname(),
                        ],
                        'pergunta' => $questao->getPergunta(),
                    ]
                ], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
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
            'resposta' => $resposta->getResposta(),
            'correta' => $resposta->isCorreta(),
        ], $respostas->toArray());
        return response()->json(['data' => $response]);
    }

    public function createResposta(Questao $questao, Request $request)
    {
        try {
            $questao = $this->questaoFacade->addAlternativa($questao, $request->get('resposta'), $request->get('isCorreta'));
            EntityManager::persist($questao);
            EntityManager::flush();
            $respostas = $questao->getRespostas();
            return response()->json(
                ['data' =>
                    [
                        'id' => $questao->getId(),
                        'tema' => [
                            'id' => $questao->getTema()->getId(),
                            'name' => $questao->getTema()->getName(),
                            'slugname' => $questao->getTema()->getSlugname(),
                        ],
                        'pergunta' => $questao->getPergunta(),
                        'respostas' => [
                            array_map(fn($resposta) => [
                                'id' => $resposta->getId(),
                                'resposta' => $resposta->getResposta(),
                                'correta' => $resposta->isCorreta(),
                            ], $respostas->toArray())
                        ]
                    ]
                ], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
}