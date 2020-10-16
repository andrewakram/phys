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

//Route::get('/', function () {
//    return view('pages-login');
//});
//Auth::routes();

Route::get('{any}', 'AdmiriaController@index');

Route::group(['prefix' => '/admin','namespace' => 'Admin'], function () {
    Route::get('/login', 'AuthController@login_view');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
        Route::get('/users', 'UserController@index')->name('users');
    });
});
