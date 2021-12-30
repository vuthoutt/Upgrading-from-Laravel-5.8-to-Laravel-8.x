<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToComplianceDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance_documents', function (Blueprint $table) {
            $table->integer('enforcement_deadline')->nullable();
            $table->integer('document_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compliance_documents', function (Blueprint $table) {
            $table->dropColumn('enforcement_deadline');
            $table->dropColumn('document_status');
        });
    }
}
