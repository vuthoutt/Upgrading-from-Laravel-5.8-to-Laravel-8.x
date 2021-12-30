<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFloorsBelowOtherToTblPropertySurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property_survey', function (Blueprint $table) {
            $table->string('floors_below_other')->after('floors_below')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_property_survey', function (Blueprint $table) {
            $table->dropColumn('floors_below_other');
        });
    }
}
