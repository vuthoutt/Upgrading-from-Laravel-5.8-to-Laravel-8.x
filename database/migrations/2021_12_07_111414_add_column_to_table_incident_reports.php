<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTableIncidentReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incident_reports', function (Blueprint $table) {
            $table->boolean('is_address_in_wcc')->default(true);
            $table->string('address_building_name')->nullable();
            $table->string('address_street_number')->nullable();
            $table->string('address_street_name')->nullable();
            $table->string('address_town')->nullable();
            $table->string('address_county')->nullable();
            $table->string('address_postcode')->nullable();
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
            $table->dropColumn('is_address_in_wcc');
            $table->dropColumn('address_building_name');
            $table->dropColumn('address_street_number');
            $table->dropColumn('address_street_name');
            $table->dropColumn('address_town');
            $table->dropColumn('address_county');
            $table->dropColumn('address_postcode');
        });
    }
}
