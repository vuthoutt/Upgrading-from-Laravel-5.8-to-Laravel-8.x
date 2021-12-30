<?php


use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;


class CreateTblAppAuditTrailTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()

    {

//        Schema::create('tbl_app_audit_trail', function (Blueprint $table) {
//
//            $table->bigIncrements('id');
//
//            $table->string('reference')->nullable();
//
//            $table->integer('user_id')->nullable();
//
//            $table->integer('type')->nullable();
//
//            $table->integer('action_type')->nullable();
//
//            $table->string('object_id')->nullable();
//
//            $table->string('comment')->nullable();
//
//            $table->integer('timestamp')->nullable();
//
//            $table->string('app_version')->nullable();
//
//            $table->string('ip')->nullable();
//
//            $table->string('device_id')->nullable();
//
//            $table->string('device_soft_version')->nullable();
//
//            $table->timestamps();
//
//        });

    }


    /**

     * Reverse the migrations.

     *

     * @return void

     */

    public function down()

    {

        Schema::dropIfExists('tbl_app_audit_trail');

    }

}
