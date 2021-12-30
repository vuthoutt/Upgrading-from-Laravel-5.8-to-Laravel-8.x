<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssessTypeToCpHazardSpecificLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazard_specific_location', function (Blueprint $table) {
            $table->integer('assess_type')->default(4)->after('dropdown_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazard_specific_location', function (Blueprint $table) {
            $table->dropColumn('assess_type');
        });
    }
}
