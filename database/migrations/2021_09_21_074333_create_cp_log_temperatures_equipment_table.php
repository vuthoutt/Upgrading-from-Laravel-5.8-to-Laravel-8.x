<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpLogTemperaturesEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_log_temperatures_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('equipment_id')->nullable();
            $table->integer('assess_id')->nullable();
            $table->string('return_temp')->nullable();
            $table->string('flow_temp')->nullable();
            $table->string('inlet_temp')->nullable();
            $table->string('stored_temp')->nullable();
            $table->string('top_temp')->nullable();
            $table->string('bottom_temp')->nullable();
            $table->string('flow_temp_gauge_value')->nullable();
            $table->string('return_temp_gauge_value')->nullable();
            $table->string('ambient_area_temp')->nullable();
            $table->string('incoming_main_pipe_work_temp')->nullable();
            $table->string('ph')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_log_temperatures_equipment');
    }
}
