<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRoleFunctions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::table('role_functions', function($table)
           {
				$table->dropColumn('function');
				$table->string('role_function', 20)->nullable();
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
		$table->dropColumn('role_function');
		   });
	}

}
