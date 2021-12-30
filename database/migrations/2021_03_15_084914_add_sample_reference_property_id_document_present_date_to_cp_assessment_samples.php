<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSampleReferencePropertyIdDocumentPresentDateToCpAssessmentSamples extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_samples', function (Blueprint $table) {
            $table->integer('property_id')->after('id');
            $table->string('sample_reference')->after('property_id');
            $table->tinyInteger('document_present')->after('assess_id');
            $table->integer('date')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_samples', function (Blueprint $table) {
            //
        });
    }
}
