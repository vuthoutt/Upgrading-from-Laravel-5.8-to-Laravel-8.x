<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCpJobRolesViewValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_job_roles_view_value', function (Blueprint $table) {
            $table->dropColumn('common_data_centre');
            $table->dropColumn('common_reporting');
            $table->dropColumn('common_property_information');
            $table->dropColumn('general_property_listing');
            $table->dropColumn('general_email_notifications');
            $table->dropColumn('general_organisational');
            $table->dropColumn('general_resources');
            $table->dropColumn('general_audit_trail');
            $table->dropColumn('general_site_operative_view');
            $table->dropColumn('general_view_trouble_ticket');
            $table->dropColumn('general_work_request');
            $table->string('common_everything')->nullable()->change();
            $table->string('general_is_operative')->default(0)->change();
            $table->string('general_is_tt')->default(0)->change();
            $table->mediumText("common_static_values_view")->nullable();
            $table->mediumText("common_dynamic_values_view")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_job_roles_view_value', function (Blueprint $table) {
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
            $table->string('general_work_request');
            $table->string('common_everything')->nullable()->change();
            $table->string('general_is_operative')->default(0)->change();
            $table->string('general_is_tt')->default(0)->change();
            $table->dropColumn('common_static_values_view');
            $table->dropColumn('common_dynamic_values_view');
        });
    }
}
