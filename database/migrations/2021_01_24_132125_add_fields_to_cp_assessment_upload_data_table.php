<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCpAssessmentUploadDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_upload_data', function (Blueprint $table) {
            $table->integer('assess_id')->after('id');
            $table->integer('manifest_id')->after('id');
            $table->tinyInteger('status')->after('assess_id')->comment('');
            $table->mediumText('data')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_upload_data', function (Blueprint $table) {
            $table->dropColumn('assess_id');
            $table->dropColumn('manifest_id');
            $table->dropColumn('status');
            $table->dropColumn('data');
        });
    }
}
