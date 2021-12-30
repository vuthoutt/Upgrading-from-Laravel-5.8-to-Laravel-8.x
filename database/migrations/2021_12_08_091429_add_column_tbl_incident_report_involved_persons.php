<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTblIncidentReportInvolvedPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_report_involved_persons', function (Blueprint $table) {
            $table->string('non_user')->nullable()->after('user_id');
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
            $table->dropColumn('non_user');
        });
    }
}
