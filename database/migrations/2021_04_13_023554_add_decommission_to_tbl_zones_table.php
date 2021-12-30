<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDecommissionToTblZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_zones', function (Blueprint $table) {
            $table->tinyInteger('decommissioned')->after('zone_name')->nullable()->default(0);
            $table->integer('decommissioned_reason')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_zones', function (Blueprint $table) {
            $table->dropColumn('decommissioned');
            $table->dropColumn('decommissioned_reason');
        });
    }
}
