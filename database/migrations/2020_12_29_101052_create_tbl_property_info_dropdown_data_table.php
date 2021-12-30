<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPropertyInfoDropdownDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_property_info_dropdown_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_info_dropdown_id');
            $table->string('description');
            $table->integer('order');
            $table->boolean('other')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_property_info_dropdown_data');
    }
}
