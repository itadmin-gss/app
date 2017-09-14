<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orders extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('orders', function ($table) {
                $table->increments('id');
                $table->integer('request_id')->unsigned()->nullable();
                $table->foreign('request_id')->references('id')->on('maintenance_requests')->onDelete('cascade');
                $table->integer('vendor_id')->unsigned()->nullable();
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
                $table->integer('total_amount', false, true)->nullable();
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
            Schema::drop('orders');
    }
}
