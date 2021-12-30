<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFireSafetyToCpAssessmentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_info', function (Blueprint $table) {
            $table->string('fire_safety')->after('assess_id')->nullable();
            $table->string('fire_safety_other')->after('assess_id')->nullable();
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
            $table->dropColumn('fire_safety');
            $table->dropColumn('fire_safety_other');
        });
    }
}
