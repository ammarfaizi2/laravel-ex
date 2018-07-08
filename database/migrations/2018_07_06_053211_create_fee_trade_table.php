<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeeTradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fee_trade', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->float('fee_sell', 10, 0)->nullable();
			$table->float('fee_buy', 10, 0)->nullable();
			$table->integer('market_id')->nullable()->unique('market_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fee_trade');
	}

}
