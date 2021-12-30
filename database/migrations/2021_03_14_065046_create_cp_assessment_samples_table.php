<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_samples', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable();
            $table->integer('assess_id')->nullable();
            $table->string('document_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('latest_version')->nullable();
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
        Schema::dropIfExists('cp_assessment_samples');
    }
}
