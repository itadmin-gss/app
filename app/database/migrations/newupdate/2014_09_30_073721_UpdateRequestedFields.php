<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRequestedFields extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('requested_services', function($table) {
            $table->string('verified_vacancy', 60)->nullable();
            $table->string('cash_for_keys', 60)->nullable();
            $table->string('cash_for_keys_trash_out', 60)->nullable();
            $table->string('trash_size', 60)->nullable();
            $table->string('storage_shed', 60)->nullable();
            $table->string('lot_size', 60)->nullable();
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
