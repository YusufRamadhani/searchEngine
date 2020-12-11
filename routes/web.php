<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/mainpage', 'ControllerTesting@index');
Route::post('/search', 'ControllerTesting@search');

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/mainpage', 'SearchController@index');
// Route::post('/search', 'SearchController@search');
