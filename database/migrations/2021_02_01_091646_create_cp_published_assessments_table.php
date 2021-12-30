<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpPublishedAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_published_assessments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assess_id');
            $table->string('name')->nullable();
            $table->integer('revision')->nullable();
            $table->bigInteger('size')->nullable();
            $table->text('filename')->nullable();
            $table->string('mime')->nullable();
            $table->text('path')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cp_published_assessments');
    }
}
