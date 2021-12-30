<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessmentValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessment_values', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('assess_id')->nullable();
			$table->integer('question_id')->nullable();
			$table->integer('answer_id')->nullable();
			$table->string('other', 500)->nullable();
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
		Schema::drop('cp_assessment_values');
	}

}
