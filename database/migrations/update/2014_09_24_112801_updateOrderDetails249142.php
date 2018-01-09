<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderDetails249142 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('order_details', function ($table) {
                $table->integer('requested_service_id')->unsigned()->nullable();
                $table->foreign('requested_service_id')->references('id')->on('requested_services')->onDelete('cascade');
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
