<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => ['web']], function () {
    
    Route::auth();
    
    
    Route::get('/testajax', 'AjaxController@test');
    Route::get('/file','AjaxController@file');
    
    
    /* Page navigation */
    Route::get('/', 'MainController@index');
    Route::get('profile','MainController@profile');
    Route::get('profile/edit', 'MainController@editProfile');
    Route::get('profile/{id}', 'MainController@guestProfile');
    Route::get('charts', 'MainController@charts');
    Route::get('info', function(){
        return view('info');
    });
    
    /* Admin navigation */
    Route::get('admin','AdminController@index');
    Route::get('admin/statistics', 'AdminController@statistics');
    Route::get('admin/exercise','AdminController@exerciseIndex');
    Route::get('admin/exercise/edit','AdminController@exercise');
    Route::get('admin/exercise/edit/{id}','AdminController@exercise');
    Route::get('admin/exprogram','AdminController@exprogramIndex');
    Route::get('admin/exprogram/edit','AdminController@ex_program');
    Route::get('admin/exprogram/edit/{id}','AdminController@ex_program');
    Route::get('admin/healthinfo','AdminController@healthinfoIndex');
    Route::get('admin/healthinfo/edit','AdminController@healthinfo');
    Route::get('admin/healthinfo/edit/{id}','AdminController@healthinfo');
	Route::get('admin/notification','AdminController@notificationIndex');
    Route::get('admin/notification/edit','AdminController@notification');
    Route::get('admin/notification/edit/{id}','AdminController@notification');
	
    /* Creation of new items */
    Route::post('new/exercise', 'AdminController@storeExercise');
    Route::post('new/ex_program', 'AdminController@storeExerciseProgram');
	Route::post('save/notification', 'AdminController@storeNotification');
	Route::post('save/healthinfo', 'AdminController@storeHealthinfo');
    
    //Route::resource('images','ImageController');
    
    /* Ajax communication */
    Route::post('get/subdivisions', 'AjaxController@getSubdivisions');
    Route::post('{id}/get/subdivisions', 'AjaxController@getSubdivisions');
    Route::get('get/events','AjaxController@events')->middleware('auth');
    Route::get('get/next', 'AjaxController@getNextEvent')->middleware('auth');
    Route::post('get/snooze', 'AjaxController@snooze')->middleware('auth');
    Route::post('get/skip', 'AjaxController@skipEvent')->middleware('auth');
    Route::post('get/complete', 'AjaxController@completeEvent')->middleware('auth');
    Route::get('stats/personal', 'AjaxController@personalStats')->middleware('auth');
    Route::post('send/friendRequest', 'AjaxController@sendFriendRequest')->middleware('auth');
    Route::get('accept/friendRequest/{id}', 'AjaxController@acceptFriendRequest')->middleware('auth');
    Route::get('decline/friendRequest/{id}', 'AjaxController@declineFriendRequest')->middleware('auth');
	Route::post('survey/submit', 'AjaxController@submitSurvey')->middleware('auth');
    
    /* Settings */
    Route::post('edit/profile', 'SettingsController@handleSettingsChange');
    Route::post('edit/calendar', 'SettingsController@handleCalendarSettingsChange');
    Route::post('edit/profilepic', 'SettingsController@handleProfilepicChange');
    Route::post('password/confirm', 'SettingsController@confirmOldPassword');
    Route::post('password/change', 'SettingsController@changePassword');
    
    /* Password security */
    Route::get('password/change/confirm/{confirmationCode}', ['uses' => 'AjaxController@confirmPasswordChange']);
    
    /* Chat routes */
    Route::post('message/send', 'ChatController@sendMessage');
    Route::delete('message/remove', 'ChatController@removeMessage');
    Route::get('get/chat/lastID', 'ChatController@getMissedMessageIDs');
    Route::get('update/chat', 'ChatController@updateChat');
    
    /*Registration verification*/
    Route::get('register/verify/{confirmationCode}', ['uses' =>'RegistrationController@verify'])->middleware('block-verified');
    Route::get('register/notverified', 'RegistrationController@showVerificationPage')->middleware(['auth','block-verified']);
    Route::get('register/sendnewcode', 'RegistrationController@newVerificationCode')->middleware(['auth','block-verified']);
	
});

