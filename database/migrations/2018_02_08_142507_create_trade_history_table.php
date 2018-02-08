<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTradeHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trade_history', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('market_id')->nullable()->index('market_id');
			$table->integer('seller_id')->nullable();
			$table->integer('buyer_id')->nullable();
			$table->enum('type', array('sell','buy'))->nullable()->comment('buy or sell');
			$table->float('amount', 10, 0)->nullable();
			$table->float('price', 10, 0)->nullable();
			$table->float('fee_sell', 10, 0)->nullable();
			$table->float('fee_buy', 10, 0)->nullable();
			$table->timestamps();
			$table->index(['market_id','created_at'], 'market_id_2');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('trade_history');
	}

}
