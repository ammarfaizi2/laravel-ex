<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMarketNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('market_news', function(Blueprint $table)
		{
			$table->foreign('wallet_id', 'market_news_ibfk_2')->references('id')->on('wallets')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('market_news', function(Blueprint $table)
		{
			$table->dropForeign('market_news_ibfk_2');
		});
	}

}
