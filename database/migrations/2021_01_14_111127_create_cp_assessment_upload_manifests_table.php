<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentUploadManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_upload_manifests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assess_id');
            $table->integer('assessor_id');
            $table->tinyInteger('status')->comment('1: Uploaded, 2: Processed, 9: Error');
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
        Schema::dropIfExists('cp_assessment_upload_manifests');
    }
}
