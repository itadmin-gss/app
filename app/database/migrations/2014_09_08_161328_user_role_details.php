<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserRoleDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('user_role_details', function($table)
            {   
                $table->increments('id');
                $table->integer('role_id')->unsigned()->nullable();
                $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('cascade');;
                $table->integer('role_function_id')->unsigned()->nullable();
                $table->foreign('role_function_id')->references('id')->on('role_functions')->onDelete('cascade');;
                $table->boolean('add')->nullable();
                $table->boolean('edit')->nullable();
                $table->boolean('delete')->nullable();
                $table->boolean('status')->nullable();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('user_role_details');
	}

}
