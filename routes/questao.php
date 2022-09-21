<?php

Route::get('questoes','QuestoesController@index');
Route::post('questoes','QuestoesController@store');

Route::get('questoes/{questao}','QuestoesController@listRepostas');
Route::put('questoes/{questao}','QuestoesController@createRespostas');