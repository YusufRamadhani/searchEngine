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
    return view('mainpage');
});

Route::get('/mainpage', 'SearchController@index');
Route::post('/search', 'SearchController@search');
Route::get('/show/{loglivechatid}', 'SearchController@show')->name('chat.show');

Route::prefix('admin')->group(function () {
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('logout', 'Auth\AdminLoginController@logout')->name('logout');
    Route::post('logout', 'Auth\AdminLoginController@logout')->name('logout');
    Route::get('/', 'Auth\AdminController@index')->name('admin.dashboard');

    Route::get('indexterm', 'IndexController@index')->name('index.term');
    Route::post('createterm', 'IndexController@createIndex')->name('create.term');
    Route::post('createdoc', 'ControllerTesting@setDocument')->name('create.doc');

    Route::get('edit/importantword', 'ImportantWordController@edit')->name('edit.importantword');
});
Route::resource('importantword', 'ImportantWordController');

Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register')->name('register');
