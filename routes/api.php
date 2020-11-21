<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/user'], function () {
    //Start apis authentication
    Route::post('/login', 'Api\User\AuthController@login');
    Route::post('/update_profile', 'Api\User\AuthController@updateProfile');
    Route::get('/exams', 'Api\User\ExamsController@examData');
    Route::post('/examData', 'Api\User\ExamsController@examQuestions');

    Route::get('/my-exams', 'Api\User\ExamsController@myExams');
    Route::post('/finish-exam', 'Api\User\ExamsController@finishExam');


    //sessions

    Route::get('/sessions', 'Api\User\SessionsController@Sessions');
    Route::get('/sessionDetails/{id}', 'Api\User\SessionsController@SessionDetails');
});
