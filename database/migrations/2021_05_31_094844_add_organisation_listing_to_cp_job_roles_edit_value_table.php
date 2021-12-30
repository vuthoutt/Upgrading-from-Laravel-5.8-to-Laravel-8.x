<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganisationListingToCpJobRolesEditValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_job_roles_edit_value', function (Blueprint $table) {
            $table->mediumText('organisation_listing')->nullable();
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
            $table->dropColumn('organisation_listing');
        });
    }
}
