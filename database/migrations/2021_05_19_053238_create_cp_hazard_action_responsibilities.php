<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpHazardActionResponsibilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_hazard_action_responsibilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(1);
            $table->integer('other')->default(0);
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
        Schema::dropIfExists('cp_hazard_action_responsibilities');
    }
}
