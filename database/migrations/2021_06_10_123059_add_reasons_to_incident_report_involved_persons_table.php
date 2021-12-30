<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonsToIncidentReportInvolvedPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_report_involved_persons', function (Blueprint $table) {
            $table->string('injury_type')->after('user_id')->nullable();
            $table->string('injury_type_other')->after('injury_type')->nullable();
            $table->string('part_of_body_affected')->after('injury_type_other')->nullable();
            $table->string('apparent_cause')->after('part_of_body_affected')->nullable();
            $table->string('apparent_cause_other')->after('apparent_cause')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incident_report_involved_persons', function (Blueprint $table) {
            $table->dropColumn('apparent_cause');
            $table->dropColumn('apparent_cause_other');
            $table->dropColumn('injury_type');
            $table->dropColumn('injury_type_other');
            $table->dropColumn('part_of_body_affected');
        });
    }
}
