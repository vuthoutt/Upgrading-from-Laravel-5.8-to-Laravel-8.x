<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpAssessmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_assessments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('client_id')->nullable()->index('GroupbyContractor');
			$table->integer('property_id')->nullable()->index('GroupbyProperty');
			$table->boolean('type')->nullable()->index('survey_type')->comment('was report_type');
			$table->string('reference', 50)->nullable()->index('ref');
			$table->boolean('status')->nullable()->index('status')->comment('1=New,2=Locked (down to iPhone),3=Unlocked,4=Published');
			$table->boolean('is_locked')->nullable();
			$table->boolean('decommissioned')->nullable()->default(0)->index('decommissioned');
			$table->integer('lead_by')->nullable()->index('lead_by');
			$table->integer('second_lead_by')->nullable();
			$table->smallInteger('assessor_id')->nullable()->index('surveyor_id');
			$table->integer('project_id')->nullable()->index('project_id');
			$table->integer('work_request_id')->nullable();
			$table->integer('sent_out_date')->nullable();
			$table->integer('sent_back_date')->nullable();
			$table->integer('due_date')->nullable();
			$table->integer('published_date')->nullable();
			$table->integer('completed_date')->nullable();
			$table->integer('started_date')->nullable();
			$table->integer('assess_start_date')->nullable();
			$table->integer('assess_finish_date')->nullable();
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
		Schema::drop('cp_assessments');
	}

}
