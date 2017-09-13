<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
                {   
                    $table->increments('id');
                    $table->string('first_name', 20)->nullable();
                    $table->string('last_name', 20)->nullable();
                    $table->string('company', 30)->nullable();
                    $table->string('email', 30)->unique();
                    $table->string('username', 20)->unique();
                    $table->string('password', 60)->nullable();
                    $table->string('phone', 60)->nullable();
                    $table->text('address_1')->nullable();
                    $table->text('address_2')->nullable();
                    $table->integer('city', false, true)->nullable();
                    $table->integer('state', false, true)->nullable();
                    $table->string('zipcode', 60)->nullable();
                    $table->string('profile_image', 200)->nullable();
                    $table->integer('type_id', false, true)->nullable();
                    $table->integer('user_role_id', false, true)->nullable();
                    $table->boolean('profile_status')->default(0)->nullable();
                    $table->boolean('status')->nullable();
                    $table->string('remember_token', 100)->nullable();
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
		Schema::drop('users');
	}

}
