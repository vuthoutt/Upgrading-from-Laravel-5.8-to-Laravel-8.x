<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonDecommissionedToCpFireExitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_fire_exits', function (Blueprint $table) {
            $table->integer('reason_decommissioned')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_fire_exits', function (Blueprint $table) {
            $table->dropColumn('reason_decommissioned');
        });
    }

}
