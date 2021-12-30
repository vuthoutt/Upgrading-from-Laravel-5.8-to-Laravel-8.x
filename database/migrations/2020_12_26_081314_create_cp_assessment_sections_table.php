<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessmentSectionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessment_sections', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('description')->nullable();
			$table->string('order')->nullable();
			$table->string('type')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_assessment_sections');
	}

}
