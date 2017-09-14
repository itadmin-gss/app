<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssignRequests extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('assign_requests', function ($table) {
                $table->increments('id');
                $table->integer('request_id')->unsigned()->nullable();
                $table->foreign('request_id')->references('id')->on('maintenance_requests')->onDelete('cascade');
                $table->integer('requested_service_id')->unsigned()->nullable();
                $table->foreign('requested_service_id')->references('id')->on('requested_services')->onDelete('cascade');
                $table->integer('vendor_id')->unsigned()->nullable();
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
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
            Schema::drop('assign_requests');
    }
}
