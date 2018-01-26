<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNotificationActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notification_activities', function(Blueprint $table)
		{
			$table->foreign('notification_id')->references('id')->on('notifications')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notification_activities', function(Blueprint $table)
		{
			$table->dropForeign('notification_activities_notification_id_foreign');
		});
	}

}
