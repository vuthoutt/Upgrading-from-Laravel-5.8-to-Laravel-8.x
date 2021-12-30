<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificLocationToCpHazardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazards', function (Blueprint $table) {
            $table->integer('specific_location')->after('act_recommendation_verb_other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazards', function (Blueprint $table) {
            $table->dropColumn('specific_location');
        });
    }
}
