<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePoolsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pools', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('coin_id');
			$table->string('url');
			$table->timestamps();
			$table->string('blockviewer');
			$table->string('website');
			$table->string('forum');
			$table->text('info', 65535);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pools');
	}

}
