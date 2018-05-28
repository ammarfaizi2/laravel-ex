<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWalletLimittradeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wallet_limittrade', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('wallet_id');
			$table->float('min_amount', 10, 0);
			$table->float('max_amount', 10, 0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('wallet_limittrade');
	}

}
