<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeeWithdrawTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fee_withdraw', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->float('percent_fee', 10, 0)->nullable();
			$table->integer('wallet_id')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('fee_withdraw');
	}

}
