<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumInTableCpAssessmentNoteDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_assessment_note_documents', function (Blueprint $table) {
            $table->dropColumn('contractor');
            $table->dropColumn('size');
            $table->dropColumn('mime');
            $table->dropColumn('category');
            $table->dropColumn('rejected');
            $table->dropColumn('deleted');
            $table->dropColumn('authorised');
            $table->dropColumn('deleted_by');
            $table->dropColumn('authorised_by');
            $table->dropColumn('rejected_by');
            $table->dropColumn('deadline');
            $table->dropColumn('contractor1');
            $table->dropColumn('contractor2');
            $table->dropColumn('contractor3');
            $table->dropColumn('contractor4');
            $table->dropColumn('contractor5');
            $table->dropColumn('contractor6');
            $table->dropColumn('contractor7');
            $table->dropColumn('contractor8');
            $table->dropColumn('contractor9');
            $table->dropColumn('contractor10');
            $table->dropColumn('name');
            $table->dropColumn('filename');
            $table->dropColumn('type');
            $table->dropColumn('path');
            $table->dropColumn('note');
            $table->dropColumn('added');
            $table->integer('plan_date')->nullable();
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_assessment_note_documents', function (Blueprint $table) {
            //
        });
    }
}
