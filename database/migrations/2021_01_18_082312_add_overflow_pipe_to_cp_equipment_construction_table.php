<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOverflowPipeToCpEquipmentConstructionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_construction', function (Blueprint $table) {
            $table->boolean('overflow_pipe')->after('warning_pipe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_equipment_construction', function (Blueprint $table) {
            $table->dropColumn('overflow_pipe');
        });
    }
}
