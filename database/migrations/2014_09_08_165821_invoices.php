<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Invoices extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('invoices', function ($table) {
                $table->increments('id');
                $table->integer('order_id')->unsigned()->nullable();
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->integer('total_amount', false, true);
                $table->integer('request_id')->unsigned()->nullable();
                $table->foreign('request_id')->references('id')->on('maintenance_requests')->onDelete('cascade');
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
            Schema::drop('invoices');
    }
}
