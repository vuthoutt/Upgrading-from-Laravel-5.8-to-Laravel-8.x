<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPropertyVulnerableOccupantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_property_vulnerable_occupants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id');
            $table->decimal('avg_vulnerable_occupants', 8, 2)->nullable();
            $table->decimal('max_vulnerable_occupants', 8, 2)->nullable();
            $table->decimal('last_vulnerable_occupants', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_property_vulnerable_occupants');
    }
}
