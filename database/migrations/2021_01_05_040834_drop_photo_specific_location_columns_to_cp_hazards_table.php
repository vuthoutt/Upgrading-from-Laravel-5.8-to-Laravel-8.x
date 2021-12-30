<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropPhotoSpecificLocationColumnsToCpHazardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazards', function (Blueprint $table) {
            $table->dropColumn('specific_location');
            $table->dropColumn('location_photo_path');
            $table->dropColumn('hazard_photo_path');
            $table->dropColumn('additional_photo_path');

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
            $table->integer('specific_location')->after('act_recommendation_verb_other')->nullable();
            $table->string('location_photo_path')->after('comment')->nullable();
            $table->string('hazard_photo_path')->after('comment')->nullable();
            $table->string('additional_photo_path')->after('comment')->nullable();
        });
    }
}
