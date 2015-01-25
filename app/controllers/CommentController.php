<?php

class CommentController extends BaseController {

    public function add()
    {
        if($_FILES['comment-file']['name']!="")
        {
            $file = $_FILES['comment-file']['name'];
            $key = 'uploads/todo-'.$file;
            $s3 = AWS::get('s3');
            $s3->putObject(array( 'Bucket' => 'campusby-test',
                                  'Key' => $key,
                                  'SourceFile' => $_FILES['comment-file']['tmp_name'],
                                  'ACL' => 'public-read'
                ));
            $url = "http://campusby-test.s3.amazonaws.com/".$key;
        }
        else{
            $url = "";
        }
        $response = Comment::create(array('task_id'=>Input::get('task_id'),'text'=>Input::get('comment-text'),'url'=>$url));
        return $response->id;
    }
    public function getComments()
    {
        $response = Comment::where("task_id",Input::get('task_id'))->get();

        $responseArray = $response->toJson();

        return $responseArray;
    }

}

