<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationForeignKey extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            DB::statement('ALTER TABLE notifications MODIFY notification_type_id Int UNSIGNED NOT NULL');

		 Schema::table('notifications', function($table)
            {

                $table->foreign('notification_type_id')->references('id')->on('notification_types')->onDelete('cascade');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
