<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActionRecommendationNounVerbToCpHazardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazards', function (Blueprint $table) {
            $table->integer('act_recommendation_noun')->after('measure_id')->nullable();
            $table->string('act_recommendation_noun_other')->after('measure_id')->nullable();
            $table->integer('act_recommendation_verb')->after('measure_id')->nullable();
            $table->string('act_recommendation_verb_other')->after('measure_id')->nullable();
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
            $table->dropColumn('act_recommendation_noun');
            $table->dropColumn('act_recommendation_noun_other');
            $table->dropColumn('act_recommendation_verb');
            $table->dropColumn('act_recommendation_verb_other');
        });
    }
}
