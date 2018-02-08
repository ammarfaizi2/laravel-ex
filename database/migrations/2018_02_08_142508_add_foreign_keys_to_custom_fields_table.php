<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCustomFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('custom_fields', function(Blueprint $table)
		{
			$table->foreign('market_id', 'custom_fields_ibfk_2')->references('id')->on('market')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('custom_fields', function(Blueprint $table)
		{
			$table->dropForeign('custom_fields_ibfk_2');
		});
	}

}
