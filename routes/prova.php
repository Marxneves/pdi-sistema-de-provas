<?php

Route::get('provas','ProvaController@index');
Route::get('provas/{prova}','ProvaController@show');

Route::post('alunos/{aluno}/prova','ProvaController@store');
Route::put('provas/{prova}','ProvaController@enviarRepostas');