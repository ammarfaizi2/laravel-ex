<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeaturedMarketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('featured_market', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->string('link');
			$table->text('message', 65535);
			$table->integer('coin');
			$table->dateTime('start_date');
			$table->dateTime('end_date');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('featured_market');
	}

}
