<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentReportDropdownDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_report_dropdown_data', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('dropdown_id')->nullable();
            $table->string('description')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incident_report_dropdown_data');
    }
}
