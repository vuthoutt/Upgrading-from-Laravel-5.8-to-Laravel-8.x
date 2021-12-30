<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToCpHazardActionRecommendationNoun extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazard_action_recommendation_noun', function (Blueprint $table) {
            $table->integer('type')->default(4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazard_action_recommendation_noun', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
