<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVulnerableOccupantTypesToTblPropertyVulnerableOccupants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property_vulnerable_occupants', function (Blueprint $table) {
            $table->mediumText('vulnerable_occupant_type')->after('last_vulnerable_occupants')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_property_vulnerable_occupants', function (Blueprint $table) {
            //
        });
    }
}
