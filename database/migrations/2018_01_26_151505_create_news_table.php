<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('date_created')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('market_id')->default(0);
			$table->text('market_type', 65535)->nullable();
			$table->text('market_name', 65535)->nullable();
			$table->text('title', 65535)->nullable();
			$table->text('content')->nullable();
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
		Schema::drop('news');
	}

}
