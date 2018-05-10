<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('market_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->float('amount', 10, 0)->nullable();
			$table->float('price', 10, 0)->nullable();
			$table->float('from_value', 10, 0)->nullable();
			$table->float('to_value', 10, 0)->nullable()->comment('price * from_value');
			$table->enum('type', array('sell','buy'))->nullable()->comment('sell or buy');
			$table->string('status', 100)->nullable();
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
		Schema::drop('orders');
	}

}
