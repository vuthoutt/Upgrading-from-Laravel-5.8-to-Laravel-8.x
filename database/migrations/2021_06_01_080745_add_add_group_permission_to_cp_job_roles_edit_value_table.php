<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddGroupPermissionToCpJobRolesEditValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_job_roles_edit_value', function (Blueprint $table) {
            $table->mediumText('add_group_permission')->nullable();
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
            $table->dropColumn('add_group_permission');
        });
    }
}
