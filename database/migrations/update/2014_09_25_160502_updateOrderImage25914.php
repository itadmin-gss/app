<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderImage25914 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('order_images', function ($table) {
                $table->integer('order_details_id')->unsigned()->nullable();
                $table->foreign('order_details_id')->references('id')->on('order_details')->onDelete('cascade');
                ;
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
