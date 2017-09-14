<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAsset extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('assets', function ($table) {
                    $table->text('property_address')->nullable();
                    $table->string('lock_box', 60)->nullable();
                    $table->string('access_code', 60)->nullable();
                    $table->string('brokage', 60)->nullable();
                    $table->renameColumn('lender', 'agent');
                    $table->string('customer_email_address', 60)->nullable();
                    $table->string('carbon_copy_email', 200)->nullable();
                    $table->boolean('outbuilding_shed')->nullable();
                    $table->text('outbuilding_shed_note')->nullable();
                    $table->text('special_direction_note')->nullable();
                    $table->renameColumn('electric_note', 'utility_note');
                    $table->string('swimming_pool', 60)->nullable();
                    $table->dropColumn('gas_note');
                    $table->dropColumn('water_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
