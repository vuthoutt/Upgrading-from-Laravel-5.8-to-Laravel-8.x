<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessmentQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessment_questions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('section_id')->nullable();
			$table->string('description')->nullable();
			$table->string('score')->nullable();
			$table->integer('answer_type')->nullable();
			$table->boolean('is_key')->nullable();
			$table->string('good_answer')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_assessment_questions');
	}

}
