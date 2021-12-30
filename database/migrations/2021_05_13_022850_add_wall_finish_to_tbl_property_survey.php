<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWallFinishToTblPropertySurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property_survey', function (Blueprint $table) {
            $table->integer('wall_finish')->nullable();
            $table->integer('wall_finish_other')->nullable();
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
            $table->dropColumn('wall_finish');
            $table->dropColumn('wall_finish_other');
        });
    }
}
