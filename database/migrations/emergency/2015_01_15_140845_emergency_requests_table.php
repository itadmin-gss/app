<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmergencyRequestsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('emergency_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->unsigned()->nullable();
            $table->foreign('request_id')->references('id')->on('maintenance_requests')->onDelete('cascade');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->integrer('status')->unsigned()->nullable();
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
        //
                    Schema::drop('emergency_requests');
    }
}
