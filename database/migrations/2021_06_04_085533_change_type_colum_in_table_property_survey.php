<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeColumInTablePropertySurvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property_survey', function (Blueprint $table) {
            $table->string('stairs')->change();
            $table->string('floors')->change();
            $table->string('wall_construction')->change();
            $table->string('wall_construction_other')->change();
            $table->string('wall_finish')->change();
            $table->string('wall_finish_other')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_propety_survey', function (Blueprint $table) {
            //
        });
    }
}
