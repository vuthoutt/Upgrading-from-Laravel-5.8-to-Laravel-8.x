<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpAssemblyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_assembly_points', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable();
            $table->string('name')->nullable();
            $table->integer('property_id')->nullable();
            $table->integer('assess_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->boolean('decommissioned')->nullable();
            $table->boolean('accessibility')->nullable();
            $table->integer('reason_na')->nullable();
            $table->string('comment', 500)->nullable();
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
        Schema::dropIfExists('cp_assembly_points');
    }
}
