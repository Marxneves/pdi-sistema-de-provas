<?php

namespace App\Http\Controllers;


use App\Packages\Temas\Domain\Model\Temas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;

class TemasController extends Controller
{
    public function index()
    {
        $temas = EntityManager::getRepository(Temas::class)->findAll();
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
            $name = $request->get('name');
            $slugname = $request->get('slugname');
            $tema = EntityManager::getRepository(Temas::class)->findOneBy(['slugname' => $slugname]);
            if($tema instanceof Temas) {
                throw new \Exception('o tema já existe.');
            }
            $tema = new Temas(Str::uuid(),$name , $slugname);
            EntityManager::persist($tema);
            EntityManager::flush();
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