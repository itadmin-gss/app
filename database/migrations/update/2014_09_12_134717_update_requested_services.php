<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRequestedServices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('requested_services', function($table)
                {
                   $table->date('required_date')->nullable();
                   $table->time('required_time')->nullable();
                   $table->string('service_men', 10)->nullable();
                   $table->text('service_note')->nullable();
                  // $table->
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
