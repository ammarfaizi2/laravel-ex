<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTradeHistoryRecentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('trade_history_recent', function(Blueprint $table)
		{
			$table->integer('market_id')->primary()->comment('market_id');
			$table->float('last_price', 10, 0);
			$table->float('opening_price', 10, 0);
			$table->float('bid', 10, 0);
			$table->float('price_change', 10, 0);
			$table->float('ask', 10, 0);
			$table->float('24h_volume_coin', 10, 0);
			$table->float('24h_volume_base', 10, 0);
			$table->float('24h_low', 10, 0);
			$table->float('24h_high', 10, 0);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('trade_history_recent');
	}

}
