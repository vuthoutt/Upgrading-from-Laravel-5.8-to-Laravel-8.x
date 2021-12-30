<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRiskClassificationAndAssessmentIdsToTblProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('tbl_project', function (Blueprint $table) {
//            $table->integer('risk_classification_id')->nullable();
//            $table->string('assessment_ids')->nullable();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('tbl_project', function (Blueprint $table) {
//            $table->dropColumn('risk_classification_id');
//            $table->dropColumn('assessment_ids');
//        });
    }
}
