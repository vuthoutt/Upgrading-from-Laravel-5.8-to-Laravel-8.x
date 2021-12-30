<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingShowVehicleParkingToCpAssessmentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_info', function (Blueprint $table) {
            $table->boolean('setting_show_vehicle_parking')->after('assess_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_info', function (Blueprint $table) {
            $table->dropColumn('setting_show_vehicle_parking');
        });
    }
}
