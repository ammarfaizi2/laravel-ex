<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommissionFeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commission_fees', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('ref_user_id')->unsigned()->index('ref_user_id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('wallet_id')->index('wallet_id');
			$table->float('amount', 10, 0);
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
		Schema::drop('commission_fees');
	}

}
