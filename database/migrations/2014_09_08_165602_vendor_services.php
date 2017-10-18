<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorServices extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('vendor_services', function ($table) {
                $table->increments('id');
                $table->integer('vendor_id')->unsigned()->nullable();
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
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
            Schema::drop('vendor_services');
    }
}
