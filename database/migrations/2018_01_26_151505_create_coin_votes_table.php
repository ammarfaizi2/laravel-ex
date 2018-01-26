<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCoinVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coin_votes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 10);
			$table->string('name', 50);
			$table->string('btc_address');
			$table->string('label_address');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('coin_votes');
	}

}
