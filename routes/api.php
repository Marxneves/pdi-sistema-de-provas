<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('alunos','AlunoController@index');
Route::post('alunos','AlunoController@store');

Route::get('alunos/{aluno}/provas','ProvaController@index');
Route::post('alunos/{aluno}/prova','ProvaController@store');
Route::post('provas/{prova}','ProvaController@enviarRepostas');

Route::get('temas','TemasController@index');
Route::post('temas','TemasController@store');

Route::get('questoes','QuestoesController@index');
Route::post('questoes','QuestoesController@store');

Route::get('questoes/{questao}','QuestoesController@listRepostas');
Route::post('questoes/{questao}','QuestoesController@createResposta');
