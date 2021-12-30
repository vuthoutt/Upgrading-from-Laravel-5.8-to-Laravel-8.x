<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceToCpHazardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_hazards', function (Blueprint $table) {
            $table->string('reference', 50)->nullable()->comment('Hazard Shine Reference')->index('reference')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_hazards', function (Blueprint $table) {
            $table->dropColumn('reference');
        });
    }
}
