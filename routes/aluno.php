<?php

Route::get('alunos','AlunoController@index');
Route::post('alunos','AlunoController@store');

Route::get('alunos/{aluno}/provas','AlunoController@listProvas');