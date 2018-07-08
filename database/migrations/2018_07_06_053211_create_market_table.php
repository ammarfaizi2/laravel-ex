<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('market', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('wallet_from', 10)->nullable()->comment('match to wallet type');
			$table->string('wallet_to', 10)->nullable()->comment('match to wallet type');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('market');
	}

}
