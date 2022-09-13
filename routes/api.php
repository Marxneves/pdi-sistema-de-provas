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

Route::get('temas','TemasController@index');
Route::post('temas','TemasController@store');

Route::get('questoes','QuestoesController@index');
Route::post('questoes','QuestoesController@store');

Route::get('questoes/{questao}','QuestoesController@listRepostas');
Route::post('questoes/{questao}','QuestoesController@createResposta');

