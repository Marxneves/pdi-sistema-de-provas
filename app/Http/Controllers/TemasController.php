<?php

namespace App\Http\Controllers;


use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Facade\TemaFacade;
use Illuminate\Http\Request;

class TemasController extends Controller
{
    public function __construct(TemaRepository $temaRepository, TemaFacade $temaFacade)
    {
        $this->temaRepository = $temaRepository;
        $this->temaFacade = $temaFacade;
    }

    public function index()
    {
        $temas = $this->temaRepository->findAll();
        $response = array_map(fn($tema) => [
            'id' => $tema->getId(),
            'name' => $tema->getName(),
            'slugname' => $tema->getSlugname(),
        ], $temas);
        return response()->json(['data' => $response]);
    }

    public function store(Request $request)
    {
        try {
            $tema = $this->temaFacade->create($request->get('name'), $request->get('slugname'));
            return response()->json(
                ['data' =>
                    [
                        'id' => $tema->getId(),
                        'name' => $tema->getName(),
                        'slugname' => $tema->getSlugname(),
                    ]
                ], 201);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }
    }
}