<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMessengerMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('messenger_messages', function(Blueprint $table)
		{
			$table->foreign('user_id', 'messenger_messages_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('thread_id', 'messenger_messages_ibfk_2')->references('id')->on('messenger_threads')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('messenger_messages', function(Blueprint $table)
		{
			$table->dropForeign('messenger_messages_ibfk_1');
			$table->dropForeign('messenger_messages_ibfk_2');
		});
	}

}
