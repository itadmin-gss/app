<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Notifications extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('notifications', function ($table) {
                $table->increments('id');
                $table->integer('sender_id', false, true)->nullable();
                $table->integer('recepient_id', false, true)->nullable();
                $table->text('message')->nullable();
                $table->integer('activity_id')->unsigned()->nullable();
                $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
                $table->boolean('is_read')->nullable();
                $table->timestamp('created_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::drop('notifications');
    }
}
