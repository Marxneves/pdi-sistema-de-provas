<?php

namespace App\Http\Controllers;

use App\Http\Utilities\ErrorResponse;
use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Facade\TemaFacade;
use App\Packages\Tema\Request\TemaRequest;
use App\Packages\Tema\Response\TemaResponse;

class TemasController extends Controller
{
    public function __construct(private TemaRepository $temaRepository, private TemaFacade $temaFacade)
    {
    }

    public function index()
    {
        try {
            $temas = $this->temaFacade->getAll();
            return response()->json(['data' => TemaResponse::collection($temas)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }

    public function store(TemaRequest $request)
    {
        try {
            $tema = $this->temaFacade->create($request->get('nome'), $request->get('slugname'));
            $this->temaRepository->flush();
            return response()->json(['data' => TemaResponse::item($tema)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }
}