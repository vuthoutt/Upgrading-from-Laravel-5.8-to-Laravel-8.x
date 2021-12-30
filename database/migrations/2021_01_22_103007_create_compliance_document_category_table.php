<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplianceDocumentCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_document_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('property_id')->nullable()->index('property_id');
            $table->string('name')->nullable();
            $table->integer('type')->default(0)->nullable()->index('type');// Asbestos/Fire/Water/ME/Gas
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
        Schema::dropIfExists('compliance_document_category');
    }
}
