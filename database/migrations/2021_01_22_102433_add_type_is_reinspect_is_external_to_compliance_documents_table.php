<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeIsReinspectIsExternalToComplianceDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance_documents', function (Blueprint $table) {
            $table->integer('compliance_type')->default(0)->nullable()->index('compliance_type')->after('name');// Asbestos/Fire/Water/ME/Gas
            $table->tinyInteger('is_external_ms')->default(0)->after('type');//asbestos logic
            $table->tinyInteger('is_reinspected')->default(0)->after('is_external_ms');//compliance logic
            $table->tinyInteger('category_id')->nullable()->after('is_reinspected')->index('category_id');
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
            $table->dropColumn('compliance_type');
            $table->dropColumn('is_external_ms');
            $table->dropColumn('is_reinspected');
            $table->dropColumn('category_id');
        });
    }
}
