<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplianceDocumentStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_document_storage', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('object_id')->default(0);
            $table->string('type', 20)->default('')->index('type');
            $table->string('path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime')->nullable();
            $table->integer('size')->nullable();
            $table->integer('addedBy')->nullable();
            $table->integer('addedDate')->nullable();
            $table->timestamps();
            $table->dateTime('deteted_at')->nullable();
            $table->unique(['object_id', 'type'], 'object');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compliance_document_storage');
    }
}
