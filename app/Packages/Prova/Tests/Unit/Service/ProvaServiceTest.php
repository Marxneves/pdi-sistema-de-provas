<?php

namespace App\Packages\Prova\Tests\Unit\Service;

use App\Packages\Aluno\Domain\Model\Aluno;
use App\Packages\Prova\Domain\Model\Prova;
use App\Packages\Prova\Service\ProvaService;
use App\Packages\Questao\Domain\Model\Questao;
use App\Packages\Questao\Domain\Repository\QuestaoRepository;
use App\Packages\Tema\Domain\Model\Tema;
use App\Packages\Tema\Domain\Repository\TemaRepository;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProvaServiceTest extends TestCase
{
    public function testIfCreateProva()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaMock = $this->createStub(Tema::class);
        $questaoMock = $this->createStub(Questao::class);
        $temaRepositoryMock = $this->createStub(TemaRepository::class);
        $questaoRepositoryMock = $this->createStub(QuestaoRepository::class);

        $temaRepositoryMock->method('findOneBySlugname')->willReturn($temaMock);
        $questaoRepositoryMock->method('findRandomByTemaAndLimit')->willReturn([$questaoMock]);
        $questaoMock->method('getAlternativas')->willReturn([]);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepositoryMock);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $prova = $provaService->create($alunoMock, 'tema-teste');
        $this->assertInstanceOf(Prova::class, $prova);
    }

    public function testIfThrowExceptionQuandoTemaNaoExistir()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaRepositoryMock = $this->createStub(TemaRepository::class);
        $temaRepositoryMock->method('findOneBySlugname')->willReturn(null);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);

        $this->expectExceptionObject(new \Exception('O tema não existe.', 1663702757));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->create($alunoMock, 'tema-teste');
    }

    public function testIfThrowExceptionQuandoNaoExisteQuestoesDeUmTema()
    {
        $alunoMock = $this->createStub(Aluno::class);
        $temaMock = $this->createStub(Tema::class);
        $temaRepositoryMock = $this->createStub(TemaRepository::class);
        $questaoRepositoryMock = $this->createStub(QuestaoRepository::class);

        $temaRepositoryMock->method('findOneBySlugname')->willReturn($temaMock);
        $questaoRepositoryMock->method('findRandomByTemaAndLimit')->willReturn([]);

        $this->app->bind(TemaRepository::class, fn() => $temaRepositoryMock);
        $this->app->bind(QuestaoRepository::class, fn() => $questaoRepositoryMock);

        $this->expectExceptionObject(new \Exception('Não possuem questões para esse tema.', 1664391636));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->create($alunoMock, 'tema-teste');
    }

    public function testIfRespondeProva()
    {
        $prova = $this->createProvaForTest();
        $respostas = $this->getRespostasAlunoForaDeOrdemForTest($prova);

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $prova = $provaService->responder($prova, $respostas);

        $this->assertSame(5.0, $prova->getNota());
        $this->assertSame(Prova::CONCLUIDA, $prova->getStatus());
    }

    public function testIfThrowExceptionProvaConcluida()
    {
        $provaMock = $this->createStub(Prova::class);
        $provaMock->method('getStatus')->willReturn(Prova::CONCLUIDA);

        $this->expectExceptionObject(new \Exception('Prova já concluída.', 1663702741));

        /** @var ProvaService $provaService */
        $provaService = app(ProvaService::class);
        $provaService->responder($provaMock, []);
    }

    private function createProvaForTest(): Prova
    {
        $temaMock = $this->createStub(Tema::class);
        $prova = new Prova(Str::uuid(), $this->createStub(Aluno::class), $temaMock);
        $questoesArray = $this->createQuestoesForTest();
        $prova->setQuestoes($questoesArray);
        return $prova;
    }

    private function createQuestoesForTest(): array
    {
        $temaMock = $this->createStub(Tema::class);
        $questaoUm = new Questao('0983421b-03f6-4fc2-b034-6f926fe8f305', $temaMock, 'Qual a melhor linguagem de programação?');
        $questaoUm->setAlternativas([
            ['resposta' => 'PHP', 'isCorreta' => true],
            ['resposta' => 'Java', 'isCorreta' => false],
            ['resposta' => 'C#', 'isCorreta' => false],
            ['resposta' => 'Python', 'isCorreta' => false],
        ]);

        $questaoDois = new Questao('37b8d645-d663-4044-9ba4-ed4cda86ca80', $temaMock, 'Qual a pior linguagem de programação?');
        $questaoDois->setAlternativas([
            ['resposta' => 'PHP', 'isCorreta' => false],
            ['resposta' => 'Java', 'isCorreta' => true],
            ['resposta' => 'C#', 'isCorreta' => false],
            ['resposta' => 'Python', 'isCorreta' => false],
        ]);

        $questaoTres = new Questao('4aea10e8-0bc6-4dd8-9faa-ea2b312c1d5f', $temaMock, 'Qual a linguagem de programação mais usada?');
        $questaoTres->setAlternativas([
            ['resposta' => 'PHP', 'isCorreta' => true],
            ['resposta' => 'Java', 'isCorreta' => false],
            ['resposta' => 'C#', 'isCorreta' => false],
            ['resposta' => 'Python', 'isCorreta' => false],
        ]);

        $questaoQuatro = new Questao('a87c5ee8-2a2d-4540-8d1d-8cdc08d489ec', $temaMock, 'Qual a linguagem de programação menos usada?');
        $questaoQuatro->setAlternativas([
            ['resposta' => 'PHP', 'isCorreta' => false],
            ['resposta' => 'Java', 'isCorreta' => false],
            ['resposta' => 'C#', 'isCorreta' => true],
            ['resposta' => 'Python', 'isCorreta' => false],
        ]);

        $questoesArray = [$questaoUm, $questaoDois, $questaoTres, $questaoQuatro];
        return $questoesArray;
    }

    private function getRespostasAlunoForaDeOrdemForTest(Prova $prova): array
    {
        return [
            [
                'questaoId' => $prova->getQuestoes()[1]->getId(),
                'respostaAluno' => 'PHP',
            ],
            [
                'questaoId' => $prova->getQuestoes()[0]->getId(),
                'respostaAluno' => 'Java',
            ],
            [
                'questaoId' => $prova->getQuestoes()[3]->getId(),
                'respostaAluno' => 'C#',
            ],
            [
                'questaoId' => $prova->getQuestoes()[2]->getId(),
                'respostaAluno' => 'PHP',
            ]
        ];
    }
}