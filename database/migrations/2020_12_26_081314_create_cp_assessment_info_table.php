<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessmentInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessment_info', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('assess_id');
			$table->boolean('setting_equipment_details')->nullable();
			$table->boolean('setting_hazard_photo_required')->nullable();
			$table->boolean('setting_assessors_note_required')->nullable();
			$table->text('objective_scope', 65535)->nullable();
			$table->text('property_information', 65535)->nullable();
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
		Schema::drop('cp_assessment_info');
	}

}
