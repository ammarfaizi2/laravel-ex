<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_transactions', function(Blueprint $table)
		{
			$table->foreign('order_id', 'order_transactions_ibfk_4')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('buyer_id', 'order_transactions_ibfk_5')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('seller_id', 'order_transactions_ibfk_6')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_transactions', function(Blueprint $table)
		{
			$table->dropForeign('order_transactions_ibfk_4');
			$table->dropForeign('order_transactions_ibfk_5');
			$table->dropForeign('order_transactions_ibfk_6');
		});
	}

}
