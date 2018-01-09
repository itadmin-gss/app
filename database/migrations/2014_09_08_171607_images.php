<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Images extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('images', function ($table) {
                $table->increments('id');
                $table->integer('image_type_id')->unsigned()->nullable();
                $table->foreign('image_type_id')->references('id')->on('image_types')->onDelete('cascade');
                $table->text('image_path')->nullable();
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
            Schema::drop('images');
    }
}
