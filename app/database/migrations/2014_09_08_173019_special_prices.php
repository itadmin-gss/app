<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SpecialPrices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('special_prices', function($table)
            {   
                $table->increments('id');
                $table->integer('customer_id')->unsigned()->nullable();
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('service_id')->unsigned()->nullable();
                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
                $table->integer('special_price', false, true)->nullable();
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
            Schema::drop('special_prices');
	}

}
