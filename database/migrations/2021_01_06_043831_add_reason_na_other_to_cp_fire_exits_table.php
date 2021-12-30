<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonNaOtherToCpFireExitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_fire_exits', function (Blueprint $table) {
            $table->string('reason_na_other')->after('reason_na')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_fire_exits', function (Blueprint $table) {
            $table->dropColumn('reason_na_other');
        });
    }
}
