<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGraphicalChartTypeToCpHazardActionRecommendationVerbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazard_action_recommendation_verb', function (Blueprint $table) {
            $table->tinyInteger('graphical_chart_type')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazard_action_recommendation_verb', function (Blueprint $table) {
            $table->dropColumn('graphical_chart_type');
        });
    }
}
