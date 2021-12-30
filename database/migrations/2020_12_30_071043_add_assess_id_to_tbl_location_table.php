<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAssessIdToTblLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_location', function (Blueprint $table) {
            $table->integer('assess_id')->default(0)->after('survey_id')->index('assess_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_location', function (Blueprint $table) {
            $table->dropColumn('assess_id');
        });
    }
}
