<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeValueToCpHazardSpecificLocationValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazard_specific_location_value', function (Blueprint $table) {
            $table->string('value', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazard_specific_location_value', function (Blueprint $table) {
            $table->integer('value', 11)->nullable()->change();
        });
    }
}
