<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_transactions', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('order_id')->index('order_id');
			$table->integer('seller_id')->unsigned()->index('seller_id');
			$table->integer('buyer_id')->unsigned()->index('buyer_id');
			$table->enum('type', array('buy','sell'));
			$table->float('amount', 10, 0);
			$table->float('price', 10, 0);
			$table->float('fee_sell', 10, 0);
			$table->float('fee_buy', 10, 0);
			$table->dateTime('created_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_transactions');
	}

}
