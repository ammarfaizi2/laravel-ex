<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGiveawaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('giveaways', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('date_created')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('wallet_id')->default(0);
			$table->text('wallet_type', 65535)->nullable();
			$table->text('wallet_name', 65535)->nullable();
			$table->decimal('amount', 12, 4)->default(0.0000);
			$table->integer('time_interval')->default(0);
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
		Schema::drop('giveaways');
	}

}
