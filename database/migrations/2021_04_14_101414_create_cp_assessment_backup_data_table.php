<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentBackupDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_backup_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('backup_id')->nullable();
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('cp_assessment_backup_data');
    }
}
