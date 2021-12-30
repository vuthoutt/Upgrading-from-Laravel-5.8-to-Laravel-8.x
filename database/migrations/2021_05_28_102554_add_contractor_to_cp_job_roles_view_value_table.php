<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractorToCpJobRolesViewValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_job_roles_view_value', function (Blueprint $table) {
            $table->mediumText('contractor')->nullable();//organisation
            $table->mediumText('client_listing')->nullable();//property listing tab
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
        Schema::table('cp_job_roles_view_value', function (Blueprint $table) {
            $table->dropColumn('contractor');
            $table->dropColumn('client_listing');
            $table->dropColumn('add_group_permission');
        });
    }
}
