<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPropertyVulnerableOccupantTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_property_vulnerable_occupant_type', function (Blueprint $table) {
            $table->integer('vulnerable_occupant_id');
            $table->integer('vulnerable_occupant_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_property_vulnerable_occupant_type');
    }
}
