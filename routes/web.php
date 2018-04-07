<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


/* Branch */

Route::get('/branch','BranchController@getAll');

Route::get('/branch/{id}','BranchController@getById');

Route::post('/branch', 'BranchController@add');

Route::put('/branch/{id}','BranchController@update');

Route::delete('/branch/{id}','BranchController@delete');

/* Stops */

Route::get('/stop','StopController@getAll');

Route::get('/stop/{id}','StopController@getById');

Route::post('/stop', 'StopController@add');

Route::put('/stop/{id}','StopController@update');

Route::delete('/stop/{id}','StopController@delete');