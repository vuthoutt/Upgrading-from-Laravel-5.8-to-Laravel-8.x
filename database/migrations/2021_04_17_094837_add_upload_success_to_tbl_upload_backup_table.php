<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadSuccessToTblUploadBackupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('tbl_upload_backup', function (Blueprint $table) {
//            $table->boolean('version')->default(1);
//            $table->integer('image_count')->nullable();
//            $table->boolean('upload_success')->default(0);
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('tbl_upload_backup', function (Blueprint $table) {
//            $table->dropColumn('version');
//            $table->dropColumn('image_count');
//            $table->dropColumn('upload_success');
//        });
    }
}
