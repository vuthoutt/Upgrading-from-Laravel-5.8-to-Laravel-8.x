<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyInfoColumnsToTblPropertySurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property_survey', function (Blueprint $table) {
            $table->integer('property_status')->after('property_id')->nullable();
            $table->integer('property_occupied')->after('property_status')->nullable();
            $table->integer('listed_building')->after('construction_type')->nullable();
            $table->string('listed_building_other')->after('listed_building')->nullable();
            $table->integer('parking_arrangements')->after('size_gross_area')->nullable();
            $table->string('parking_arrangements_other')->after('parking_arrangements')->nullable();
            $table->string('nearest_hospital')->after('parking_arrangements_other')->nullable();
            $table->string('restrictions_limitations')->after('nearest_hospital')->nullable();
            $table->string('unusual_features')->after('restrictions_limitations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_property_survey', function (Blueprint $table) {
            $table->dropColumn('property_status');
            $table->dropColumn('property_occupied');
            $table->dropColumn('listed_building');
            $table->dropColumn('listed_building_other');
            $table->dropColumn('parking_arrangements');
            $table->dropColumn('parking_arrangements_other');
            $table->dropColumn('nearest_hospital');
            $table->dropColumn('restrictions_limitations');
            $table->dropColumn('unusual_features');
        });
    }
}
