<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHazardIdToVehicleParkingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_vehicle_parking', function (Blueprint $table) {
            $table->integer('hazard_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_vehicle_parking', function (Blueprint $table) {
            $table->dropColumn('hazard_id');
        });
    }
}
