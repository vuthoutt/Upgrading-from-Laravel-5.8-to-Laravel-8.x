<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreLoadedToCpAssessmentManagementInfoQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_management_info_questions', function (Blueprint $table) {
            $table->string('pre_loaded')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_management_info_questions', function (Blueprint $table) {
            $table->dropColumn('pre_loaded');
        });
    }
}
