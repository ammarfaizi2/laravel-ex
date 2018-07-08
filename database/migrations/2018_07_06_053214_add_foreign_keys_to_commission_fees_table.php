<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommissionFeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('commission_fees', function(Blueprint $table)
		{
			$table->foreign('user_id', 'commission_fees_ibfk_5')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('wallet_id', 'commission_fees_ibfk_6')->references('id')->on('wallets')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('ref_user_id', 'commission_fees_ibfk_8')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('commission_fees', function(Blueprint $table)
		{
			$table->dropForeign('commission_fees_ibfk_5');
			$table->dropForeign('commission_fees_ibfk_6');
			$table->dropForeign('commission_fees_ibfk_8');
		});
	}

}
