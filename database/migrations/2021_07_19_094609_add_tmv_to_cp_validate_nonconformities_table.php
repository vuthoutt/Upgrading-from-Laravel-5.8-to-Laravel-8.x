<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTmvToCpValidateNonconformitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_validate_nonconformities', function (Blueprint $table) {
            $table->integer('tmv')->after('field')->nullable();
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
            $table->dropColumn('tmv');
        });
    }
}
