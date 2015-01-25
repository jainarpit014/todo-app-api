<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tasks', function(Blueprint $table)
		{
			$table->create();
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('description');
            $table->string('priority');
            $table->string('flag');
            $table->timestamp('duedate')->nullable();
            $table->timestamps();
		});
       /* Schema::table('tasks', function(Blueprint $table)
        {
             $table->foreign('user_id')->references('id')->on('users');
        });*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tasks', function(Blueprint $table)
		{
			//
            $table->drop();
		});
	}

}
