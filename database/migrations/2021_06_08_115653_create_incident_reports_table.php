<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('type')->nullable();
            $table->integer('status')->nullable();
            $table->integer('date_of_report')->nullable();
            $table->integer('time_of_report')->nullable();
            $table->integer('reported_by')->nullable();
            $table->integer('property_id')->nullable();
            $table->integer('equipment_id')->nullable();
            $table->integer('system_id')->nullable();
            $table->string('details', 1000)->nullable();
            $table->boolean('confidential')->nullable()->default(0);
            $table->boolean('is_involved')->nullable()->default(0);
            $table->integer('date_of_incident')->nullable();
            $table->integer('time_of_incident')->nullable();
            $table->boolean('decommissioned')->nullable()->default(0)->index('decommissioned');
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
        Schema::dropIfExists('incident_reports');
    }
}
