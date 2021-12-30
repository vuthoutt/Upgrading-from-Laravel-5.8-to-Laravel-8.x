<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpHazardDropdownDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_hazard_inaccessible_reason', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('description')->nullable();
            $table->boolean('other')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_hazard_inaccessible_reason');
    }
}
