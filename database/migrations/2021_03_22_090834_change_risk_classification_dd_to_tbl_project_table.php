<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRiskClassificationDdToTblProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_project', function (Blueprint $table) {
//            $table->renameColumn('risk_classification_dd', 'risk_classification_id');
            $table->string('assessment_ids')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_project', function (Blueprint $table) {
//            $table->renameColumn('risk_classification_id', 'risk_classification_dd');
            $table->string('assessment_ids')->nullable();
        });
    }
}
