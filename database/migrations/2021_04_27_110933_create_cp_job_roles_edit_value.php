<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpJobRolesEditValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_job_roles_edit_value', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('role_id');
            $table->string('common_everything');
            $table->string('common_property_information');
            $table->string('common_data_centre');
            $table->string('general_organisational');
            $table->string('general_resources');
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
        Schema::dropIfExists('cp_job_roles_edit_value');
    }
}
