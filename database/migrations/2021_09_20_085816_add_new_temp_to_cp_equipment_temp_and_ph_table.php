<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewTempToCpEquipmentTempAndPhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_temp_and_ph', function (Blueprint $table) {
            $table->string('hot_flow_temp',255)->nullable();
            $table->string('cold_flow_temp',255)->nullable();
            $table->string('pre_tmv_cold_flow_temp',255)->nullable();
            $table->string('pre_tmv_hot_flow_temp',255)->nullable();
            $table->string('post_tmv_temp',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_equipment_temp_and_ph', function (Blueprint $table) {
            //
        });
    }
}
