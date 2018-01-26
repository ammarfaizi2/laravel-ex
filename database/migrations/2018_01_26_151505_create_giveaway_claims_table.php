<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGiveawayClaimsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('giveaway_claims', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->timestamp('date_created')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('wallet_id')->default(0);
			$table->integer('user_id')->default(0);
			$table->integer('giveaway_id')->default(0);
			$table->decimal('amount', 12, 4)->default(0.0000);
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
		Schema::drop('giveaway_claims');
	}

}
