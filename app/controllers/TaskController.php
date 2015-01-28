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

        Task::create(array('user_id'=>$userId,'title'=>Input::get('title'),'description'=>Input::get('description'),'priority'=>Input::get('priority'),'flag'=>'n','duedate'=>Input::get('duedate')));

        echo true;
    }
    public function getTasksWithFlag()
    {

        $originalInput = Request::input();
        $request = Request::create('/users/add', 'POST', array('email'=>Input::get('email'),'name'=>Input::get('name')));
        Request::replace($request->input());
        $userId = Route::dispatch($request)->getContent();
        Request::replace($originalInput);


        $response = Task::where(array('flag'=>Input::get('flag'),'user_id'=>$userId))->orderBy(Input::get('column'),Input::get('order'))->get();
        $responseArray = $response->toJson();

        return $responseArray;
    }
    public function updateTaskFlag()
    {
        $task = Task::find(Input::get('task'));

        $task->flag = Input::get('flag');

        $task->save();
    }
    public function updateTask()
    {
        $task = Task::find(Input::get('task_id'));
        $task->title = Input::get('title');
        $task->description = Input::get('description');
        $task->priority = Input::get('priority');
        $task->duedate = Input::get('duedate');
        if(Input::get('completed')!="")
        {
            $task->flag = Input::get('completed');
        }
	$response = $task->save();
        if($response)
        {
            return "true";
        }
        else{
            return "false";
        }
    }
    public function emailTask()
    {
        $task = Task::find(Input::get('task_id'));

        $data = array('Title'=>$task->title,'Description'=>$task->description,'Priority'=>$task->priority,'Duedate'=>$task->duedate,'AddedOn'=>$task->created_at,'LastUpdatedOn'=>'updated_at');

        Mail::send('emails.task',$data, function($message)
        {
            $message->from('todo_app@todoapp.com', 'Todo-App');
            $message->to(Input::get('email'), Input::get('name'))->subject('New Task.');
        });
        echo "ya";
    }
    public function getAllTasks()
    {
        $originalInput = Request::input();
        $request = Request::create('/users/add', 'POST', array('email'=>Input::get('email'),'name'=>Input::get('name')));
        Request::replace($request->input());
        $userId = Route::dispatch($request)->getContent();
        Request::replace($originalInput);


        $response = Task::where(array('user_id'=>$userId))->whereNotIn( 'flag', ['d'])->orderBy(Input::get('column'),Input::get('order'))->get();
	$priority = array(0=>'Low',1=>'Medium',2=>'High');
	$flag = array('a'=>'Archived','n'=>'New','c'=>'Completed');
	$respArray = $response->toArray();
	foreach($respArray as $key=>$value){
		$respArray[$key]['priority'] = $priority[$value['priority']];
		$respArray[$key]['flag'] = $flag[$value['flag']];
	}
		 
      // $responseArray = $response->toJson();

        return $respArray;
    }

}
