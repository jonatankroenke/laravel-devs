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
    //return view('welcome');
    $a = request()->only(['a', 'b']);

    dd($a);
});

Route::group(['prefix' => 'admin'], function () {

    Route::get('users/{id}/{nome?}', function ($id, $nome = '') {

        return $id . ' - ' . $nome;
    })->where(['id' => '[0-9]+', 'nome' => '[A-Za-z\s]+']);

    Route::match(['get', 'post'], 'devs', function () {
        return "devs";
    });
});

Route::resource('dev', 'DevController')->parameters(['dev' => 'id']);
Route::resource('post', 'PostController')->parameters(['post' => 'id']);
Route::resource('dev-tech', 'DevTechsController')->parameters(['dev-tech' => 'id'])->middleware('dev-tech');