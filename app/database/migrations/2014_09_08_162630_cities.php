<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cities extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('cities', function($table)
            {   
                $table->increments('id');
                $table->string('name', 20)->nullable();
                $table->integer('state_id')->unsigned()->nullable();
                $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');;
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
            Schema::drop('cities');
	}

}
