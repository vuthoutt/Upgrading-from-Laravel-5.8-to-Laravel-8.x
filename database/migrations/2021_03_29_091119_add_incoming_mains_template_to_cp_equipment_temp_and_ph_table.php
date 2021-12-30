<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIncomingMainsTemplateToCpEquipmentTempAndPhTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_temp_and_ph', function (Blueprint $table) {
            $table->string('ambient_area_temp')->after('return_temp_gauge_value')->nullable();
            $table->string('incoming_main_pipe_work_temp')->after('return_temp_gauge_value')->nullable();
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
            $table->dropColumn('ambient_area_temp');
            $table->dropColumn('incoming_main_pipe_work_temp');
        });
    }
}
