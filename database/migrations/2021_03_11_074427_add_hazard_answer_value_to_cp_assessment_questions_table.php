<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHazardAnswerValueToCpAssessmentQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_questions', function (Blueprint $table) {
            $table->string('hazard_answer_value')->after('good_answer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_questions', function (Blueprint $table) {
            $table->dropColumn('hazard_answer_value');
        });
    }
}
