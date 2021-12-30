<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblDropdownDataAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_dropdown_data_area', function (Blueprint $table) {
            $table->smallInteger('id', true);
            $table->string('description')->nullable();
            $table->smallInteger('dropdown_area_id')->nullable()->default(0)->index('Group by List');
            $table->tinyInteger('order')->nullable()->default(0);
            $table->tinyInteger('score')->nullable()->default(0);
            $table->boolean('other')->nullable()->default(0);
            $table->boolean('decommissioned')->nullable()->default(0);
            $table->smallInteger('parent_id')->nullable()->default(0)->index('Group by parent');
            $table->decimal('removal_cost', 5)->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_dropdown_data_area');
    }
}
