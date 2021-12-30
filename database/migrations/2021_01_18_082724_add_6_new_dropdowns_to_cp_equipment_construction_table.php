<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add6NewDropdownsToCpEquipmentConstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_construction', function (Blueprint $table) {
            $table->integer('drain_size')->after('ball_valve_hatch')->nullable();
            $table->integer('drain_location')->after('drain_size')->nullable();
            $table->integer('cold_feed_size')->after('drain_location')->nullable();
            $table->integer('cold_feed_location')->after('cold_feed_size')->nullable();
            $table->integer('outlet_size')->after('cold_feed_location')->nullable();
            $table->integer('outlet_location')->after('outlet_size')->nullable();

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
            $table->dropColumn('drain_size');
            $table->dropColumn('drain_location');
            $table->dropColumn('cold_feed_size');
            $table->dropColumn('cold_feed_location');
            $table->dropColumn('outlet_size');
            $table->dropColumn('outlet_location');
        });
    }
}
