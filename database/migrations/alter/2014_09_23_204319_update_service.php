<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateService extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('services', function($table) {
            $table->tinyInteger('verified_vacancy')->default(0);
            $table->tinyInteger('cash_for_keys')->default(0);
            $table->tinyInteger('cash_for_keys_trash_out')->default(0);
            $table->tinyInteger('trash_size')->default(0);
            $table->tinyInteger('storage_shed')->default(0);
            $table->tinyInteger('lot_size')->default(0);
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
