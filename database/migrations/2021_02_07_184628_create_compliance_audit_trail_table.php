<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplianceAuditTrailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compliance_audit_trail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('property_id')->nullable()->index('property_id');
            $table->string('type', 100)->nullable()->default('system');
            $table->string('shine_reference', 10)->nullable()->index('shine_ref');
            $table->string('object_type', 100)->nullable()->index('object_type');
            $table->integer('object_id')->nullable()->index('object_id');
            $table->integer('object_parent_id')->nullable();
            $table->string('object_reference')->nullable()->index('object_ref');
            $table->integer('archive_id')->nullable();
            $table->string('action_type', 100)->nullable()->index('GroupByActionType');
            $table->integer('user_id')->nullable()->default(0)->index('SearchUser');
            $table->integer('user_client_id')->nullable();
            $table->string('user_name')->nullable();
            $table->integer('date')->nullable()->index('GroupByDate');
            $table->string('ip', 25)->nullable();
            $table->string('comments', 1000)->nullable();
            $table->tinyInteger('backup')->nullable();
            $table->string('department')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('compliance_audit_trail');
    }
}
