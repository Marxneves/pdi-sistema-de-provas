<?php

Route::get('questoes','QuestoesController@index');
Route::get('questoes/{questao}','QuestoesController@show');
Route::post('questoes','QuestoesController@store');
Route::put('questoes/{questao}','QuestoesController@createAlternativas');