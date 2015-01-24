<?php

class UserController extends BaseController {

    public function add()
    {
        $user = User::where('email',Input::get('email'))->get();
        if($user->isEmpty()){
            $response = User::create(array('name'=>Input::get('name'),'email'=>Input::get('email')));
            return $response->id;
        }
        else{
            $userArray = $user->toArray();
            return $userArray[0]['id'];
        }
    }

}

