<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplianceSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_systems', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('record_id')->nullable()->index('record_id');
            $table->tinyInteger('is_locked')->default(0)->index('is_locked');
            $table->integer('property_id');
            $table->integer('assess_id')->default(0);
            $table->string('name')->nullable();
            $table->string('reference')->nullable();
            $table->integer('type')->nullable();
            $table->integer('classification')->nullable();
            $table->boolean('decommissioned')->nullable();
            $table->string('comment', 1000)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('compliance_systems');
    }
}
