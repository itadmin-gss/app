<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceImages extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('service_images', function($table) {
            $table->increments('id');
            $table->integer('requested_id')->unsigned()->nullable();
            $table->foreign('requested_id')->references('id')->on('requested_services')->onDelete('cascade');
            $table->text('image_name')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('image_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
