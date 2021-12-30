<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingFieldsToCpAssessmentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_info', function (Blueprint $table) {
            $table->boolean('setting_property_size_volume')->after('assess_id')->default(false);
            $table->boolean('setting_fire_safety')->after('assess_id')->default(false);
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
            $table->dropColumn('setting_property_size_volume');
            $table->dropColumn('setting_fire_safety');
        });
    }
}
