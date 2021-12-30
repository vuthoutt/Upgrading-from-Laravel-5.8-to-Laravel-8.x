<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsLockedToCpEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipments', function (Blueprint $table) {
            $table->tinyInteger('is_locked')->after('record_id')->default(0)->index('is_locked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_equipments', function (Blueprint $table) {
            $table->dropColumn('is_locked');
        });
    }
}
