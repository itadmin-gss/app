<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsers1 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
                {
                    $table->integer('city_id')->unsigned()->nullable();
                    $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');;
                    $table->integer('state_id')->unsigned()->nullable();
                    $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');;
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
