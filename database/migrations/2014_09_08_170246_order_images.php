<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderImages extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('order_images', function ($table) {
                $table->increments('id');
                $table->integer('order_id')->unsigned()->nullable();
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->integer('service_id')->unsigned()->nullable();
                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
                $table->enum('type', array('before', 'after'));
                $table->text('address')->nullable();
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
            Schema::drop('order_images');
    }
}
