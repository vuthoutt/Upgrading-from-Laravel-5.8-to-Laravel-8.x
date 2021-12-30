<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPreloadedCommentToCpAssessmentQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_questions', function (Blueprint $table) {
            $table->text('preloaded_comment')->after('good_answer')->nullable();
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
            $table->dropColumn('preloaded_comment');
        });
    }
}
