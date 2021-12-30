<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplianceProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_programmes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('reference')->nullable();
            $table->string('name')->nullable();
            $table->integer('system_id')->nullable();
            $table->integer('property_id')->nullable();
            $table->integer('linked_document_id')->nullable()->index('linked_document_id')->comment('For inspection of Programme');
            $table->integer('date_inspected')->nullable();
            $table->integer('next_inspection')->nullable();
            $table->boolean('decommissioned')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->integer('inspection_period')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compliance_programmes');
    }
}
