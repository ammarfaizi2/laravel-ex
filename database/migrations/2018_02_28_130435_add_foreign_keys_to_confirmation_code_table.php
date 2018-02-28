<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToConfirmationCodeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('confirmation_code', function(Blueprint $table)
		{
			$table->foreign('user_id', 'confirmation_code_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('confirmation_code', function(Blueprint $table)
		{
			$table->dropForeign('confirmation_code_ibfk_2');
		});
	}

}
