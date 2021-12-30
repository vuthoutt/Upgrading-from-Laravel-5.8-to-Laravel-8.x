<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentReportPublished extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_report_published', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('incident_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('revision')->nullable();
            $table->string('type')->nullable();
            $table->bigInteger('size')->nullable();
            $table->string('filename')->nullable();
            $table->string('mime')->nullable();
            $table->string('path')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('incident_report_published');
    }
}
