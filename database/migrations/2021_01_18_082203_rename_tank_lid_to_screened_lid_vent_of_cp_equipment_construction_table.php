<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTankLidToScreenedLidVentOfCpEquipmentConstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_construction', function (Blueprint $table) {
            $table->renameColumn('tank_lid', 'screened_lid_vent');
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
            $table->renameColumn('screened_lid_vent', 'tank_lid');
        });
    }
}
