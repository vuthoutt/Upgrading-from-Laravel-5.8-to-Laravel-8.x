<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCpAssessmentUploadImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_upload_images', function (Blueprint $table) {
            $table->integer('assess_id')->after('id');
            $table->integer('manifest_id')->after('id');
            $table->string('image_type')->after('assess_id');
            $table->string('file_name', 500)->after('image_type');
            $table->string('path', 500)->after('file_name');
            $table->string('mime')->after('path');
            $table->integer('size')->after('mime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_upload_images', function (Blueprint $table) {
            $table->dropColumn('assess_id');
            $table->dropColumn('manifest_id');
            $table->dropColumn('image_type');
            $table->dropColumn('file_name');
            $table->dropColumn('path');
            $table->dropColumn('mime');
            $table->dropColumn('size');
        });
    }
}
