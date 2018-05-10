<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWhitelistIpTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('whitelist_ip', function(Blueprint $table)
		{
			$table->foreign('user_id', 'whitelist_ip_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('whitelist_ip', function(Blueprint $table)
		{
			$table->dropForeign('whitelist_ip_ibfk_2');
		});
	}

}
