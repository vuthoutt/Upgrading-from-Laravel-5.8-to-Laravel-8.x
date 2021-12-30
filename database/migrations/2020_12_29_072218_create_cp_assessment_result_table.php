<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_result', function (Blueprint $table) {
            $table->integer('assess_id')->index('assess_id');
            $table->integer('section_id')->index('section_id');
            $table->integer('total_question');
            $table->integer('total_yes');//yes no
            $table->integer('total_no');//yes no
            $table->integer('total_score');//score of answer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_assessment_result');
    }
}
