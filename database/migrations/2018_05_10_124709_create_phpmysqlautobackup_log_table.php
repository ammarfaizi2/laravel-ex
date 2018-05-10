<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhpmysqlautobackupLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phpmysqlautobackup_log', function(Blueprint $table)
		{
			$table->integer('date')->primary();
			$table->integer('bytes');
			$table->integer('lines');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('phpmysqlautobackup_log');
	}

}
