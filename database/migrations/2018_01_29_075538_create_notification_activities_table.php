<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notification_activities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('notification_id')->unsigned();
			$table->integer('user_id')->unsigned()->nullable()->index();
			$table->string('activity');
			$table->timestamps();
			$table->index(['notification_id','user_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notification_activities');
	}

}
