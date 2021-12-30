<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractorTypesFireWaterToClientInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_client_info', function (Blueprint $table) {
            $table->boolean('type_fire_risk')->nullable();
            $table->boolean('type_fire_remedial')->nullable();
            $table->boolean('type_independent_survey')->nullable();
            $table->boolean('type_legionella_risk')->nullable();
            $table->boolean('type_water_testing')->nullable();
            $table->boolean('type_water_remedial')->nullable();
            $table->boolean('type_temperature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_client_info', function (Blueprint $table) {
            $table->dropColumn('type_fire_risk');
            $table->dropColumn('type_fire_remedial');
            $table->dropColumn('type_independent_survey');
            $table->dropColumn('type_legionella_risk');
            $table->dropColumn('type_water_testing');
            $table->dropColumn('type_water_remedial');
            $table->dropColumn('type_temperature');
        });
    }
}
