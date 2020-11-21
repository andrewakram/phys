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
    return "welcome";
});
//Auth::routes();

Route::get('{any}', 'AdmiriaController@index');

Route::group(['prefix' => '/admin', 'namespace' => 'Admin'], function () {
    Route::get('/login', 'AuthController@login_view');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index')->name('users');
            Route::post('/add', 'UserController@store')->name('addUser');
            Route::post('/edit', 'UserController@update')->name('editUser');
            Route::post('/delete', 'UserController@delete')->name('deleteUser');
            Route::post('/change-status', 'UserController@changeStatus')->name('changeStatusUser');
            Route::post('/', 'UserController@searchUsers')->name('searchUsers');
        });
        Route::group(['prefix' => 'stages'], function () {
            Route::get('/', 'StageController@index')->name('stages');
            Route::post('/add', 'StageController@store')->name('addStage');
            Route::post('/edit', 'StageController@update')->name('editStage');
            Route::post('/delete', 'StageController@delete')->name('deleteStage');
        });
        Route::group(['prefix' => 'groups'], function () {
            Route::get('/', 'GroupController@index')->name('groups');
            Route::post('/add', 'GroupController@store')->name('addGroup');
            Route::post('/edit', 'GroupController@update')->name('editGroup');
            Route::post('/delete', 'GroupController@delete')->name('deleteGroup');
            Route::post('/', 'GroupController@searchGroups')->name('searchGroups');
        });
        Route::group(['prefix' => 'exams'], function () {
            Route::get('/', 'ExamController@index')->name('exams');
            Route::post('/add', 'ExamController@store')->name('addExam');
            Route::post('/edit', 'ExamController@update')->name('editExam');
            Route::post('/delete', 'ExamController@delete')->name('deleteExam');
            Route::post('/', 'ExamController@searchExams')->name('searchExams');
        });
        Route::group(['prefix' => 'questions'], function () {
            Route::get('/', 'QuestionController@index')->name('questions');
            Route::post('/add', 'QuestionController@store')->name('addQuestion');
            Route::post('/edit', 'QuestionController@update')->name('editQuestion');
            Route::post('/delete', 'QuestionController@delete')->name('deleteQuestion');
            Route::post('/', 'QuestionController@searchQuestions')->name('searchQuestions');
        });
        Route::group(['prefix' => 'groups_exams'], function () {
            Route::get('/', 'GroupExamController@index')->name('groups_exams');
            Route::post('/add', 'GroupExamController@store')->name('addGroupExam');
            Route::post('/edit', 'GroupExamController@update')->name('editGroupExam');
            Route::post('/delete', 'GroupExamController@delete')->name('deleteGroupExam');
            Route::post('/', 'GroupExamController@searchGroupExams')->name('searchGroupExams');
        });

        Route::group(['prefix' => 'videos'], function () {
            Route::get('/', 'VideoController@index')->name('videos');
            Route::post('/add', 'VideoController@store')->name('addVideo');
            Route::post('/edit', 'VideoController@update')->name('editVideo');
            Route::post('/delete', 'VideoController@delete')->name('deleteVideo');
            Route::post('/', 'VideoController@searchVideos')->name('searchVideos');
        });

        Route::group(['prefix' => 'sessions'], function () {
            Route::get('/', 'SessionController@index')->name('sessions');
            Route::post('/add', 'SessionController@store')->name('addSession');
            Route::post('/edit', 'SessionController@update')->name('editSession');
            Route::post('/delete', 'SessionController@delete')->name('deleteSession');
        });

        Route::group(['prefix' => 'sessions_groups'], function () {
            Route::get('/', 'SessionGroupController@index')->name('sessions_groups');
            Route::post('/add', 'SessionGroupController@store')->name('addSessionGroup');
            Route::post('/edit', 'SessionGroupController@update')->name('editSessionGroup');
            Route::post('/delete', 'SessionGroupController@delete')->name('deleteSessionGroup');
            Route::post('/', 'SessionGroupController@searchSessionGroups')->name('searchSessionGroups');
        });

        Route::group(['prefix' => 'tests'], function () {
            Route::get('/', 'TestController@index')->name('tests');
            Route::post('/add', 'TestController@store')->name('addTest');
            Route::post('/edit', 'TestController@update')->name('editTest');
            Route::post('/delete', 'TestController@delete')->name('deleteTest');
            Route::post('/', 'TestController@searchTests')->name('searchTests');
        });
        Route::group(['prefix' => 'querries'], function () {
            Route::get('/', 'QuerryController@index')->name('querries');
            Route::post('/add', 'QuerryController@store')->name('addQuerry');
            Route::post('/edit', 'QuerryController@update')->name('editQuerry');
            Route::post('/delete', 'QuerryController@delete')->name('deleteQuerry');
            Route::post('/', 'QuerryController@searchQuerries')->name('searchQuerries');
        });

        Route::group(['prefix' => 'promocodes'], function () {
            Route::get('/', 'PromocodeController@index')->name('promocodes');
            Route::post('/add', 'PromocodeController@store')->name('addPromocode');
            Route::post('/edit', 'PromocodeController@update')->name('editPromocode');
            Route::post('/delete', 'PromocodeController@delete')->name('deletePromocode');
            Route::post('/', 'PromocodeController@searchPromocodes')->name('searchPromocodes');
            Route::post('/getGroups', 'PromocodeController@getGroups')->name('getGroups');
            Route::post('/getUsers', 'PromocodeController@getUsers')->name('getUsers');
        });
    });
});
