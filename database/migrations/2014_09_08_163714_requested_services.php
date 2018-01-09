<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RequestedServices extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('requested_services', function ($table) {
                $table->increments('id');
                $table->integer('requested_id')->unsigned()->nullable();
                $table->foreign('requested_id')->references('id')->on('maintenance_requests')->onDelete('cascade');
                $table->integer('service_id')->unsigned()->nullable();
                $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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
            Schema::drop('requested_services');
    }
}
