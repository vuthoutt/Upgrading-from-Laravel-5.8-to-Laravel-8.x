<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonDecommissionedForTblSurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_survey', function (Blueprint $table) {
            $table->integer('reason_decommissioned')->nullable()->after('decommissioned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_survey', function (Blueprint $table) {
            $table->dropColumn('reason_decommissioned');
        });
    }
}
