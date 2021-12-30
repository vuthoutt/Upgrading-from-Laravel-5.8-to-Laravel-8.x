<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsComplianceToTblPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_property', function (Blueprint $table) {
            $table->tinyInteger('is_compliance')->default(0)->after('pblock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_property', function (Blueprint $table) {
            $table->dropColumn('is_compliance');
        });
    }
}
