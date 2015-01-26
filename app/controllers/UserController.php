<?php

class UserController extends BaseController {

    public function add()
    {
        $user = User::where('email',Input::get('email'))->get();
        if($user->isEmpty()){
	    $pass = $this->generateRandomString();
            $defaultPassword = Hash::make($pass);
            $response = User::create(array('name'=>Input::get('name'),'email'=>Input::get('email'),'password'=>$defaultPassword));

            $data = array('email'=>Input::get('email'),'password'=>$pass);

            Mail::send('emails.credential',$data, function($message)
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
    public function createApp()
    {
        $user = User::find(Auth::user()->id);
        if($user->user_auth)
        {
		$oauthClient = OauthClient::find($user->user_auth);
            return View::make('client_token')->with('client_data',array($oauthClient->id,$oauthClient->secret));
        }
        else{
            return View::make('create_app');
        }
    }

}

