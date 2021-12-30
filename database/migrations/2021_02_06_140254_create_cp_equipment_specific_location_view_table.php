<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpEquipmentSpecificLocationViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_equipment_specific_location_view', function (Blueprint $table) {
            $table->integer('equipment_id')->unique('item_id_index');
            $table->longText('specific_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_equipment_specific_location_view');
    }
}
