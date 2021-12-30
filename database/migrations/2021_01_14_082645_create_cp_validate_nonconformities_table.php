<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpValidateNonconformitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_validate_nonconformities', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('template_id')->nullable();
			$table->string('field')->nullable();
			$table->string('nonconform_type')->nullable();
			$table->string('hazard_name')->nullable();
			$table->string('hazard_type')->nullable();
			$table->string('answer_type')->nullable();
			$table->string('value')->nullable();
			$table->string('value_max')->nullable();
			$table->string('value_min')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_validate_nonconformities');
	}

}
