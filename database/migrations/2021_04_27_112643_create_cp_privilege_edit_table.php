<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpPrivilegeEditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_privilege_edit', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('note', 50);//for comment
            $table->string('name');
            $table->tinyInteger('type');
            $table->integer('parent_id');
            $table->integer('order');
            $table->tinyInteger('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_privilege_edit');
    }
}
