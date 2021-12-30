<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblPropertyVulnerableOccupantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property_vulnerable_occupants', function (Blueprint $table) {
            $table->string('avg_vulnerable_occupants')->nullable()->change();
            $table->string('max_vulnerable_occupants')->nullable()->change();
            $table->string('last_vulnerable_occupants')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_property_vulnerable_occupants', function (Blueprint $table) {
            $table->decimal('avg_vulnerable_occupants', 8, 2)->nullable();
            $table->decimal('max_vulnerable_occupants', 8, 2)->nullable();
            $table->decimal('last_vulnerable_occupants', 8, 2)->nullable();
        });
    }
}
