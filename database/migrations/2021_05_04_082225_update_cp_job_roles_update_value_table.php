<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCpJobRolesUpdateValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_job_roles_edit_value', function (Blueprint $table) {
            $table->dropColumn('common_property_information');
            $table->dropColumn('common_data_centre');
            $table->dropColumn('general_organisational');
            $table->dropColumn('general_resources');
            $table->string('common_everything')->nullable()->change();
            $table->mediumText("common_static_values_update")->nullable();
            $table->mediumText("common_dynamic_values_update")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_job_roles_edit_value', function (Blueprint $table) {
            $table->string('common_property_information');
            $table->string('common_data_centre');
            $table->string('general_organisational');
            $table->string('general_resources');
            $table->dropColumn('common_static_values_update');
            $table->dropColumn('common_dynamic_values_update');
        });
    }
}
