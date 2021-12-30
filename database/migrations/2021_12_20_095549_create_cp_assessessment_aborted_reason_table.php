<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessessmentAbortedReasonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessment_aborted_reason', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('description')->nullable();
			$table->integer('is_deleted')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_assessment_aborted_reason');
	}

}
