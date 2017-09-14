<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('alter table users modify first_name varchar(55)');
                DB::statement('alter table users modify last_name varchar(55)');
                DB::statement('alter table users modify email varchar(100)');
                DB::statement('alter table users modify username varchar(100)');
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
