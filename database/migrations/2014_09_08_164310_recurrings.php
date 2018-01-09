<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recurrings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('recurrings', function ($table) {
                $table->increments('id');
                $table->integer('request_service_id')->unsigned()->nullable();
                $table->foreign('request_service_id')->references('id')->on('requested_services')->onDelete('cascade');
                $table->timestamp('start_date');
                $table->timestamp('end_date');
                $table->integer('duration', false, true)->nullable();
                $table->integer('vendor_id')->unsigned()->nullable();
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
                $table->enum('assignment_type', ['single', 'multiple']);
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
        Schema::drop('recurrings');
    }
}
