<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessengerMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messenger_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('thread_id')->unsigned()->index('thread_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->text('body', 65535);
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
		Schema::drop('messenger_messages');
	}

}
