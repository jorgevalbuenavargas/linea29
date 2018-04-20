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
    return view('busRoutes');
});

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/ramal', function () {
    return view('branch');
})->middleware('auth');

Route::get('/paradas', function () {
    return view('stops');
})->middleware('auth');

/* Branch */

Route::get('/branch','BranchController@getAll')->middleware('auth');

Route::get('/branch/{id}','BranchController@getById')->middleware('auth');

Route::post('/branch', 'BranchController@add')->middleware('auth');

Route::put('/branch/{id}','BranchController@update')->middleware('auth');

Route::delete('/branch/{id}','BranchController@delete')->middleware('auth');

/* Stops */

Route::get('/stop','StopController@getAll')->middleware('auth');

Route::get('/stop/{id}','StopController@getById')->middleware('auth');

Route::post('/stop', 'StopController@add')->middleware('auth');

Route::put('/stop/{id}','StopController@update')->middleware('auth');

Route::delete('/stop/{id}','StopController@delete')->middleware('auth');

/* Recorridos */

Route::get('/busRoutes','BranchController@getAll');

Route::get('/busRoutes/{id}','BranchController@getById');

// Otros

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');