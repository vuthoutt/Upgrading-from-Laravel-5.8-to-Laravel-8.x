<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssessmentPlansDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assessment_plans_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference')->nullable()->index();
            $table->integer('property_id')->index();
            $table->string('name');
            $table->string('plan_reference')->nullable();
            $table->integer('assess_id')->nullable()->index();
            $table->tinyInteger('document_present')->nullable();
            $table->string('type')->nullable();
            $table->string('contractor')->nullable();
            $table->string('size')->nullable();
            $table->text('filename')->nullable();
            $table->string('mime')->nullable();
            $table->string('path')->nullable();
            $table->text('note')->nullable();
            $table->integer('category')->nullable();
            $table->integer('added')->nullable();
            $table->integer('rejected')->nullable();
            $table->integer('deleted')->nullable();
            $table->integer('authorised')->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('authorised_by')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->integer('deadline')->nullable();
            $table->tinyInteger('contractor1')->nullable();
            $table->tinyInteger('contractor2')->nullable();
            $table->tinyInteger('contractor3')->nullable();
            $table->tinyInteger('contractor4')->nullable();
            $table->tinyInteger('contractor5')->nullable();
            $table->tinyInteger('contractor6')->nullable();
            $table->tinyInteger('contractor7')->nullable();
            $table->tinyInteger('contractor8')->nullable();
            $table->tinyInteger('contractor9')->nullable();
            $table->tinyInteger('contractor10')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_assessment_plans_documents');
    }
}
