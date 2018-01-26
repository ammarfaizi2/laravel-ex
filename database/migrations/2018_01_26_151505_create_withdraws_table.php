<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWithdrawsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('withdraws', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->nullable();
			$table->integer('wallet_id')->nullable();
			$table->text('to_address', 65535)->nullable();
			$table->float('amount', 10, 0)->nullable();
			$table->float('fee_amount', 10, 0)->nullable();
			$table->float('receive_amount', 10, 0)->nullable()->comment('amount - fee_amount');
			$table->string('confirmation_code');
			$table->timestamps();
			$table->integer('status')->nullable();
			$table->string('transaction_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('withdraws');
	}

}
