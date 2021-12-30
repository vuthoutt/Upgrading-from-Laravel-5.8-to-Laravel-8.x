<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessmentPlansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessment_plans', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('reference', 50)->nullable()->index('reference_3')->comment('PD0000 / SP0000');
			$table->integer('assess_id')->nullable()->index('GroupByPropertyandSurvey');
			$table->string('plan_reference')->nullable()->default('0');
			$table->string('decription')->nullable();
			$table->boolean('is_note')->nullable();
			$table->text('note', 65535)->nullable();
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
		Schema::drop('cp_assessment_plans');
	}

}
