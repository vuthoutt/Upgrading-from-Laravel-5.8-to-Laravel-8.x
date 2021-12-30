<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpJobRolesViewValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_job_roles_view_value', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('role_id');
            $table->string('common_everything');
            $table->string('common_data_centre');
            $table->string('common_reporting');//RCF will not have this section
            $table->string('common_property_information');
            $table->string('general_property_listing');
            $table->string('general_email_notifications');
            $table->string('general_organisational');
            $table->string('general_resources');
            $table->string('general_audit_trail');
            $table->string('general_site_operative_view');
            $table->string('general_view_trouble_ticket');
            $table->tinyInteger('general_is_operative');
            $table->tinyInteger('general_is_tt');
            $table->string('general_work_request');
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
        Schema::dropIfExists('cp_job_roles_view_value');
    }
}
