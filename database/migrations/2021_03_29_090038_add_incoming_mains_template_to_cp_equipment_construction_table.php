<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIncomingMainsTemplateToCpEquipmentConstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_construction', function (Blueprint $table) {
            $table->string('access')->after('equipment_id')->nullable();
            $table->boolean('water_meter_fitted')->after('access')->nullable();
            $table->string('water_meter_reading')->after('water_meter_fitted')->nullable();
            $table->string('material_of_pipework')->after('water_meter_reading')->nullable();
            $table->string('size_of_pipework')->after('material_of_pipework')->nullable();
            $table->string('condition_of_pipework')->after('size_of_pipework')->nullable();
            $table->string('stop_tap_fitted')->after('insulation_condition')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_equipment_construction', function (Blueprint $table) {
            $table->dropColumn('access');
            $table->dropColumn('water_meter_fitted');
            $table->dropColumn('water_meter_reading');
            $table->dropColumn('material_of_pipework');
            $table->dropColumn('size_of_pipework');
            $table->dropColumn('condition_of_pipework');
            $table->dropColumn('stop_tap_fitted');
        });
    }
}
