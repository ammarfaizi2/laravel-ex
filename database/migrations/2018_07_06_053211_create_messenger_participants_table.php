<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessengerParticipantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messenger_participants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('thread_id')->unsigned()->index('thread_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->dateTime('last_read')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('messenger_participants');
	}

}
