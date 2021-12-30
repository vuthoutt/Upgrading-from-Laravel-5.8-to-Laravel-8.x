<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSystemIdProgramIdEquipmentIdInTableComplianceDocumentCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance_document_category', function (Blueprint $table) {
            $table->dropColumn('system_id');
            $table->dropColumn('program_id');
            $table->dropColumn('equipment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compliance_document_category', function (Blueprint $table) {
            //
        });
    }
}
