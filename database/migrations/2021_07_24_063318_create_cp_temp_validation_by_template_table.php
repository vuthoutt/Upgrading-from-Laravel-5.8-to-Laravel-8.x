<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpTempValidationByTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_temp_validation_by_template', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('template_id')->nullable();
            $table->boolean('tmv')->nullable()->default(0);
            $table->string('flow_temp_gauge_value_min', 11)->nullable();
            $table->string('flow_temp_gauge_value_max')->nullable();
            $table->integer('check_equal_flow_temp_gauge')->nullable();
            $table->string('return_temp_gauge_value_min')->nullable();
            $table->string('return_temp_gauge_value_max')->nullable();
            $table->integer('check_equal_return_temp_gauge')->nullable();
            $table->string('flow_temp_min')->nullable();
            $table->string('flow_temp_max')->nullable();
            $table->integer('check_equal_flow_temp')->nullable();
            $table->string('inlet_temp_min')->nullable();
            $table->string('inlet_temp_max')->nullable();
            $table->integer('check_equal_inlet_temp')->nullable();
            $table->string('stored_temp_min')->nullable();
            $table->string('stored_temp_max')->nullable();
            $table->integer('check_equal_stored_temp')->nullable();
            $table->string('top_temp_min')->nullable();
            $table->string('top_temp_max')->nullable();
            $table->integer('check_equal_top_temp')->nullable();
            $table->string('bottom_temp_min')->nullable();
            $table->string('bottom_temp_max')->nullable();
            $table->integer('check_equal_bottom_temp')->nullable();
            $table->string('return_temp_min')->nullable();
            $table->string('return_temp_max')->nullable();
            $table->integer('check_equal_return_temp')->nullable();
            $table->string('incoming_main_pipe_work_temp_min')->nullable();
            $table->string('incoming_main_pipe_work_temp_max')->nullable();
            $table->integer('check_equal_incoming_temp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_temp_validation_by_template');
    }
}
