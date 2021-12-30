<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHzNameActionRecommendationVerbNounToCpAssessmentQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_questions', function (Blueprint $table) {
            $table->string('hz_name')->nullable();
            $table->integer('hz_verb_id')->nullable();
            $table->integer('hz_noun_id')->nullable();
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
            $table->dropColumn('hz_name');
            $table->dropColumn('hz_verb_id');
            $table->dropColumn('hz_noun_id');
        });
    }
}
