<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('fullname');
			$table->string('username');
			$table->string('email');
			$table->string('password');
			$table->string('confirmation_code');
			$table->boolean('confirmed')->default(0);
			$table->boolean('banned')->default(0);
			$table->string('authy');
			$table->string('two_factor_auth');
			$table->timestamps();
			$table->dateTime('lastest_login')->nullable();
			$table->string('timeout', 50);
			$table->integer('referral')->nullable();
			$table->string('trade_key');
			$table->string('ip_lastlogin');
			$table->string('remember_token')->nullable();
			$table->text('google2fa_secret', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
