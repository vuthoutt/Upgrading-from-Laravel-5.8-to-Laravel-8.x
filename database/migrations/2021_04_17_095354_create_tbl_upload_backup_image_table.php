<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUploadBackupImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('tbl_upload_backup_image', function (Blueprint $table) {
//            $table->integer('id', true);
//            $table->integer('backup_id')->nullable();
//            $table->integer('app_id');
//            $table->string('type')->nullable();
//            $table->string('path')->nullable();
//            $table->string('file_name')->nullable();
//            $table->string('size')->nullable();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_upload_backup_image');
    }
}
