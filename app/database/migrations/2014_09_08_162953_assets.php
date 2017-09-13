<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Assets extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('assets', function($table)
            {   
                $table->increments('id');
                $table->integer('asset_number', false, true);
                $table->integer('customer_id')->unsigned()->nullable();
                $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');;
                $table->text('address')->nullable();
                $table->integer('city_id')->unsigned()->nullable();
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');;
                $table->integer('state_id')->unsigned()->nullable();
                $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');;
                $table->integer('zip', false, true)->nullable();
                $table->string('loan_number', 30)->nullable();
                $table->string('property_type', 20);
                $table->string('lender', 30);
                $table->string('property_status', 20);
                $table->boolean('electric_status')->nullable();
                $table->boolean('waterst_atus')->nullable();
                $table->boolean('gas_status')->nullable();
                $table->text('electric_note')->nullable();
                $table->text('gas_note')->nullable();
                $table->text('water_note')->nullable();
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
            Schema::drop('assets');
	}

}
