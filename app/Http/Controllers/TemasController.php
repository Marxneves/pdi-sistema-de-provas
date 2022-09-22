<?php

namespace App\Http\Controllers;

use App\Http\Utilities\ErrorResponse;
use App\Http\Utilities\HttpStatusConstants;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use App\Packages\Tema\Facade\TemaFacade;
use App\Packages\Tema\Response\TemaResponse;
use Illuminate\Http\Request;

class TemasController extends Controller
{
    public function __construct(private TemaRepository $temaRepository, private TemaFacade $temaFacade)
    {
    }

    public function index()
    {
        $temas = $this->temaFacade->getAll();
        return response()->json(['data' => TemaResponse::collection($temas)], HttpStatusConstants::OK);
    }

    public function store(Request $request)
    {
        try {
            $tema = $this->temaFacade->create($request->get('name'), $request->get('slugname'));
            $this->temaRepository->flush();
            return response()->json(['data' => TemaResponse::item($tema)], HttpStatusConstants::OK);
        } catch (\Exception $exception) {
            return response()->json(ErrorResponse::item($exception), HttpStatusConstants::BAD_REQUEST);
        }
    }
}