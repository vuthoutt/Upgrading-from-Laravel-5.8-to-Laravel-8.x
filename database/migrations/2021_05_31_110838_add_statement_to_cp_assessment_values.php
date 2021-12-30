<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatementToCpAssessmentValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_values', function (Blueprint $table) {
            $table->string('statement')->after('other')->nullable();
            $table->text('statement_other')->after('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_values', function (Blueprint $table) {
            $table->dropColumn('statement');
            $table->dropColumn('statement_other');
        });
    }
}
