<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewTempTypeToCpTempValidationByTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_temp_validation_by_template', function (Blueprint $table) {
            $table->string('hot_flow_temp_min',255)->nullable();
            $table->string('hot_flow_temp_max',255)->nullable();
            $table->string('check_equal_hot_flow_temp',255)->nullable();
            $table->string('cold_flow_temp_min',255)->nullable();
            $table->string('cold_flow_temp_max',255)->nullable();
            $table->string('check_equal_cold_flow_temp',255)->nullable();
            $table->string('pre_tmv_cold_flow_temp_min',255)->nullable();
            $table->string('pre_tmv_cold_flow_temp_max',255)->nullable();
            $table->string('check_pre_tmv_cold_flow_temp',255)->nullable();
            $table->string('pre_tmv_hot_flow_temp_min',255)->nullable();
            $table->string('pre_tmv_hot_flow_temp_max',255)->nullable();
            $table->string('check_pre_tmv_hot_flow_temp',255)->nullable();
            $table->string('post_tmv_temp_min',255)->nullable();
            $table->string('post_tmv_temp_max',255)->nullable();
            $table->string('check_pre_tmv_post_tmv_temp',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_temp_validation_by_template', function (Blueprint $table) {
            //
        });
    }
}
