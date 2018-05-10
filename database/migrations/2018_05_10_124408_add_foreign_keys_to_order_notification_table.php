<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderNotificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_notification', function(Blueprint $table)
		{
			$table->foreign('order_id', 'order_notification_ibfk_2')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_notification', function(Blueprint $table)
		{
			$table->dropForeign('order_notification_ibfk_2');
		});
	}

}
