<?php

namespace Database\Seeders;


use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Tema\Domain\Model\Tema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use LaravelDoctrine\ORM\Facades\EntityManager;

class TemasQuestoesAlternativasSeed extends Seeder
{
    public function run()
    {
        $seeds = json_decode(file_get_contents(storage_path("seeds/temas_questoes_alternativas.json")), true);
        $tema = new Tema(Str::uuid(), $seeds['tema']['nome'], $seeds['tema']['slugname']);
        EntityManager::persist($tema);
        foreach ($seeds['questoes'] as $questao) {
            $questaoEntity = new Questao(Str::uuid(),$tema, $questao['pergunta']);
            EntityManager::persist($questaoEntity);
            foreach ($questao['alternativas'] as $alternativa) {
                $questaoEntity->addAlternativa($alternativa['alternativa'], $alternativa['correta']);
            }
        }
        EntityManager::flush();
    }
}