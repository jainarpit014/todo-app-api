<?php

class UserTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Eloquent::unguard();

        // $this->call('UserTableSeeder');
        User::create(array('name'=>'Jitendra Sisodiya','email'=>'jitendrasisodiya22@gmail.com'));
    }

}
