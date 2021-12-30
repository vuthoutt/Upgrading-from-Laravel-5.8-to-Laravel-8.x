<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToCpEquipmentTemplateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cp_equipment_template_sections', function (Blueprint $table) {
            $table->string('field')->after('section_id')->nullable();
            $table->boolean('required')->after('field')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cp_equipment_template_sections', function (Blueprint $table) {
            $table->dropColumn('field');
            $table->dropColumn('required');
        });
    }
}
