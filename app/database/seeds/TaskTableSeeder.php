<?php

class TaskTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Eloquent::unguard();

        // $this->call('UserTableSeeder');
        Task::create(array('user_id'=>1,'title'=>'This is a task1','description'=>'This is a description','priority'=>'Low','flag'=>'n'));
        Task::create(array('user_id'=>1,'title'=>'This is a task2','description'=>'This is a description','priority'=>'Low','flag'=>'n'));
        Task::create(array('user_id'=>1,'title'=>'This is a task3','description'=>'This is a description','priority'=>'Low','flag'=>'n'));
        Task::create(array('user_id'=>1,'title'=>'This is a task4','description'=>'This is a description','priority'=>'Low','flag'=>'c'));
        Task::create(array('user_id'=>1,'title'=>'This is a task5','description'=>'This is a description','priority'=>'Low','flag'=>'n'));
        Task::create(array('user_id'=>1,'title'=>'This is a task','description'=>'This is a description','priority'=>'Low','flag'=>'a'));
        Task::create(array('user_id'=>1,'title'=>'This is a task','description'=>'This is a description','priority'=>'Low','flag'=>'n'));
        Task::create(array('user_id'=>1,'title'=>'This is a task','description'=>'This is a description','priority'=>'Low','flag'=>'c'));

    }

}
