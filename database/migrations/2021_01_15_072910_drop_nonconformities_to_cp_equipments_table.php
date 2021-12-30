<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropNonconformitiesToCpEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipments', function (Blueprint $table) {
            $table->dropColumn('nonconformities');
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
            $table->boolean('nonconformities')->nullable()->index('nonconformities');
        });
    }
}
