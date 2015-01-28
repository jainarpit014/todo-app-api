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

/*Route::get('users',['before'=>'oauth','uses'=> function()
{
   return User::all();
}]);*/
Route::get('tasks',['before'=>'oauth','uses'=>  function()
{
    return Task::all();
}]);
/*Route::get('/comments', function()
{
    return Comments::all();
});*/

Route::post('gettasks', ['before'=>'oauth','uses'=>'TaskController@getTasksWithFlag']);
Route::post('savetask',['before'=>'oauth','uses'=>'TaskController@add']);
Route::post('updatetaskflag',['before'=>'oauth','uses'=>'TaskController@updateTaskFlag']);
Route::post('updatetask',['before'=>'oauth','uses'=>'TaskController@updateTask']);
Route::post('savecomment',['before'=>'oauth','uses'=>'CommentController@add']);
Route::post('getcomments',['before'=>'oauth','uses'=>'CommentController@getComments']);
Route::post('sendmail',['before'=>'oauth','uses'=>'TaskController@emailTask']);
Route::post('oauth/access_token', 'OAuthController@accessToken');
Route::get('createapp',['before'=>'auth.basic','uses'=>'UserController@createApp']);
Route::post('id_secret','OAuthController@generateIdSecret');
