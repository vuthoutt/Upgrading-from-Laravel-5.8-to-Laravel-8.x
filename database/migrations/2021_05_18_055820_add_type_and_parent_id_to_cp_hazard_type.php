<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeAndParentIdToCpHazardType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazard_type', function (Blueprint $table) {
            $table->integer('type')->default(4);
            $table->integer('parent_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazard_type', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('parent_id');
        });
    }
}
