<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckEqualToCpValidateNonconformitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_validate_nonconformities', function (Blueprint $table) {
            $table->tinyInteger('check_equal')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_validate_nonconformities', function (Blueprint $table) {
            $table->dropColumn('check_equal');
        });
    }
}
