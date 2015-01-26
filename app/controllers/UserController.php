<?php

class UserController extends BaseController {

    public function add()
    {
        $user = User::where('email',Input::get('email'))->get();
        if($user->isEmpty()){

            $defaultPassword = Hash::make($this->generateRandomString());
            $response = User::create(array('name'=>Input::get('name'),'email'=>Input::get('email'),'password'=>$defaultPassword));

            $data = array('email'=>Input::get('email'),'password'=>$defaultPassword);

            Mail::send('credential.task',$data, function($message)
            {
                $message->from('todo_app@todoapp.com', 'Todo-App');
                $message->to(Input::get('email'), Input::get('name'))->subject('Your Password to access api');
            });

            return $response->id;
        }
        else{
            $userArray = $user->toArray();
            return $userArray[0]['id'];
        }
    }
    public function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}

