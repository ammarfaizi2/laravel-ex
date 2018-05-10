<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarketNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('market_news', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('wallet_id')->index('wallet_id');
			$table->string('title');
			$table->text('content', 65535);
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
		Schema::drop('market_news');
	}

}
