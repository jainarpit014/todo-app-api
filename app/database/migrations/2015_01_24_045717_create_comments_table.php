<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function(Blueprint $table)
		{
			//
            $table->create();
            $table->increments('id');
            $table->integer('task_id');
            $table->string('text');
            $table->string('url');
            $table->timestamps();
		});
        /*Schema::table('comments', function(Blueprint $table)
        {
            //
            $table->foreign('task_id')->references('id')->on('tasks');
        });*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function(Blueprint $table)
		{
			//
		});
	}

}
