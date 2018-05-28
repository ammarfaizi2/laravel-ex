<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWalletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wallets', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('type', 10);
			$table->string('name');
			$table->string('wallet_username');
			$table->string('wallet_password');
			$table->string('wallet_ip', 45);
			$table->string('port', 45);
			$table->text('download_wallet_client', 65535)->nullable();
			$table->text('logo_coin', 65535)->nullable();
			$table->boolean('enable_deposit')->default(1);
			$table->boolean('enable_withdraw')->default(1);
			$table->integer('confirm_count')->default(1);
			$table->enum('enable_trading', array('0','1'))->default('1');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('wallets');
	}

}
