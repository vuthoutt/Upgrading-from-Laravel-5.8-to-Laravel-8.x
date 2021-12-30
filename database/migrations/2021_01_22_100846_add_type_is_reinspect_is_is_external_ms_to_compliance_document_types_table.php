<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeIsReinspectIsIsExternalMsToComplianceDocumentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance_document_types', function (Blueprint $table) {
            $table->integer('type')->default(0)->nullable()->index('type');// Asbestos/Fire/Water/ME/Gas
            $table->tinyInteger('is_external_ms')->default(0)->after('type');//asbestos logic
            $table->tinyInteger('is_reinspected')->default(0)->after('is_external_ms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compliance_document_types', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('is_external_ms');
            $table->dropColumn('is_reinspected');
        });
    }
}
