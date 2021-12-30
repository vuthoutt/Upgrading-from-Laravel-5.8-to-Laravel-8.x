<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsIdentifiedAcmIsInaccessRoomToComplianceDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compliance_documents', function (Blueprint $table) {
            $table->tinyInteger('is_identified_acm')->default(0);//asbestos logic
            $table->tinyInteger('is_inaccess_room')->default(0);//asbestos logic
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
            $table->dropColumn('is_identified_acm');
            $table->dropColumn('is_inaccess_room');
        });
    }
}
