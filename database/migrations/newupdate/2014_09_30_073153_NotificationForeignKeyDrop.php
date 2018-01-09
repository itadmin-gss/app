<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationForeignKeyDrop extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          DB::statement('alter table notifications DROP FOREIGN KEY notifications_activity_id_foreign');
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
