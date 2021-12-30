<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentReportDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_report_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('incident_report_id')->index();
            $table->string('path')->nullable();
            $table->text('filename')->nullable();
            $table->string('mime')->nullable();
            $table->string('size')->nullable();
            $table->integer('added_by')->nullable();
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
        Schema::dropIfExists('incident_report_doctuments');
    }
}
