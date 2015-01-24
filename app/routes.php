<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/',function(){
   return View::make('index');
});

Route::post('/users/add', 'UserController@add');

Route::get('/users', function()
{
	//return View::make('hello');
   return User::all();
});
Route::get('/tasks', function()
{
    //return View::make('hello');
    return Task::all();
});
Route::any('gettasks', 'TaskController@getTasksWithFlag');
Route::post('savetask','TaskController@add');
Route::any('updatetaskflag','TaskController@UpdateTaskFlag');
Route::any('updatetask','TaskController@UpdateTask');