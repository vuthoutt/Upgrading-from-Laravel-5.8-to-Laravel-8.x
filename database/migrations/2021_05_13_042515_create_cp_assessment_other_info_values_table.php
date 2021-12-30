<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentOtherInfoValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_other_info_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('assess_id');
            $table->bigInteger('question_id');
            $table->bigInteger('answer_id')->nullable();
            $table->string('answer_other')->nullable();
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
        Schema::dropIfExists('cp_assessment_other_info_values');
    }
}
