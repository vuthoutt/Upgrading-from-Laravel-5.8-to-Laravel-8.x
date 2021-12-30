<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewTempToCpLogTemperaturesEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_log_temperatures_equipment', function (Blueprint $table) {
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
        Schema::table('cp_log_temperatures_equipment', function (Blueprint $table) {
            $table->dropColumn('hot_flow_temp');
            $table->dropColumn('cold_flow_temp');
            $table->dropColumn('pre_tmv_cold_flow_temp');
            $table->dropColumn('pre_tmv_hot_flow_temp');
            $table->dropColumn('post_tmv_temp');
        });
    }
}
