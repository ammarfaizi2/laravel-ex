<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('custom_fields', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('coin_id');
			$table->string('name')->index('name');
			$table->text('value', 65535);
			$table->enum('type', array('text','link','number'));
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
		Schema::drop('custom_fields');
	}

}
