<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonDecommissionedToCpAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessments', function (Blueprint $table) {
            $table->integer('reason_decommissioned')->after('decommissioned')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessments', function (Blueprint $table) {
            $table->dropColumn('reason_decommissioned');
        });
    }
}
