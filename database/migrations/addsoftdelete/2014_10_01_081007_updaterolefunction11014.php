<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updaterolefunction11014 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('role_functions', function ($table) {
                $table->softDeletes(); // <-- This will add a deleted_at field
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
    }
}
