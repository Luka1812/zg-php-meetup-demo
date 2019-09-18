<?php

Route::get('/numbers.list', 'NumbersController@index');

Route::post('/numbers.list', 'NumbersController@index');

Route::get('/numbers.info', 'NumbersController@show');

Route::post('/numbers.create', 'NumbersController@store');

Route::post('/numbers.update', 'NumbersController@update');

Route::post('/numbers.delete', 'NumbersController@delete');

Route::post('/numbers.split', 'NumbersController@split');

Route::post('/numbers.choose', 'NumbersChooseController@choose');

Route::post('/numbers.choose.release', 'NumbersChooseController@release');

Route::post('/numbers.choose.select', 'NumbersChooseController@select');