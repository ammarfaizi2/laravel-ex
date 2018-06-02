<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTradeHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('trade_history', function(Blueprint $table)
		{
			$table->foreign('order_id', 'trade_history_ibfk_2')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('trade_history', function(Blueprint $table)
		{
			$table->dropForeign('trade_history_ibfk_2');
		});
	}

}
