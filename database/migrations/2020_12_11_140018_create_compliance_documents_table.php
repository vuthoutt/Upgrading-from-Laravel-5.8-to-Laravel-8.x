<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplianceDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference', 50)->nullable()->index('ref');
            $table->integer('property_id')->nullable()->index('property_id');
            $table->integer('equipment_id')->nullable();
            $table->integer('programme_id')->nullable();
            $table->tinyInteger('parent_type')->nullable();//equiment or programme
            $table->integer('type')->nullable();//document type
            $table->string('type_other')->nullable();//document type
            $table->integer('status')->nullable();
            $table->integer('system_id')->nullable()->index('system_id');
            $table->integer('date')->nullable();
            $table->string('name')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compliance_documents');
    }
}
