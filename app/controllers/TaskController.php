<?php

class TaskController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function add()
    {
        $originalInput = Request::input();
        $request = Request::create('/users/add', 'POST', array('email'=>Input::get('email'),'name'=>Input::get('name')));
        Request::replace($request->input());
        $userId = Route::dispatch($request)->getContent();
        Request::replace($originalInput);
        Task::create(array('user_id'=>$userId,'title'=>Input::get('title'),'description'=>Input::get('description'),'priority'=>Input::get('priority'),'flag'=>'n'));

        echo true;
    }
    public function getTasksWithFlag()
    {
        $originalInput = Request::input();
        $request = Request::create('/users/add', 'POST', array('email'=>Input::get('email'),'name'=>Input::get('name')));
        Request::replace($request->input());
        $userId = Route::dispatch($request)->getContent();
        Request::replace($originalInput);


        $response = Task::where(array('flag'=>Input::get('flag'),'user_id'=>$userId))->get();
        $responseArray = $response->toJson();

        return $responseArray;
    }
    public function UpdateTaskFlag()
    {
        $task = Task::find(Input::get('task'));

        $task->flag = Input::get('flag');

        $task->save();
    }

}
