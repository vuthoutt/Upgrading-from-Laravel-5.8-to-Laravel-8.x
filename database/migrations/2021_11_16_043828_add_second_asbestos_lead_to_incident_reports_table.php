<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondAsbestosLeadToIncidentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->integer('second_asbestos_lead')->nullable()->after('asbestos_lead');
            $table->string('call_centre_team_member_name')->nullable()->after('second_asbestos_lead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->dropColumn('second_asbestos_lead');
            $table->dropColumn('call_centre_team_member_name');
        });
    }
}
