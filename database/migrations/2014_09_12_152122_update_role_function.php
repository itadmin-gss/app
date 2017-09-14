<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRoleFunction extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('role_functions', function($table)
           {
			$table->integer('access_function_id')->unsigned()->nullable();
		   });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('role_functions', function($table)
           {
			$table->dropColumn('access_function_id');
		   });
	}

}
