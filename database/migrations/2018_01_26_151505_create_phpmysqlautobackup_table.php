<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePhpmysqlautobackupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('phpmysqlautobackup', function(Blueprint $table)
		{
			$table->integer('id')->primary();
			$table->string('version', 6)->nullable();
			$table->integer('time_last_run');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('phpmysqlautobackup');
	}

}
