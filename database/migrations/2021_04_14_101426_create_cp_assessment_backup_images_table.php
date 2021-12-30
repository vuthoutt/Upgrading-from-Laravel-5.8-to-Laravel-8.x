<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentBackupImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_backup_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('backup_id')->nullable();
            $table->integer('app_id')->nullable();
            $table->string('type')->nullable();
            $table->string('path', 500)->nullable();
            $table->string('file_name')->nullable();
            $table->integer('size')->nullable();
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
        Schema::dropIfExists('cp_assessment_backup_images');
    }
}
