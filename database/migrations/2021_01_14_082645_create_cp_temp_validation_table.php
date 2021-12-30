<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpTempValidationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_temp_validation', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('type_id')->nullable();
			$table->boolean('tmv')->nullable()->default(0);
			$table->string('flow_temp_gauge_value_min', 11)->nullable();
			$table->string('flow_temp_gauge_value_max')->nullable();
			$table->string('return_temp_gauge_value_min')->nullable();
			$table->string('return_temp_gauge_value_max')->nullable();
			$table->string('flow_temp_min')->nullable();
			$table->string('flow_temp_max')->nullable();
			$table->string('inlet_temp_min')->nullable();
			$table->string('inlet_temp_max')->nullable();
			$table->string('stored_temp_min')->nullable();
			$table->string('stored_temp_max')->nullable();
			$table->string('top_temp_min')->nullable();
			$table->string('top_temp_max')->nullable();
			$table->string('bottom_temp_min')->nullable();
			$table->string('bottom_temp_max')->nullable();
			$table->string('return_temp_min')->nullable();
			$table->string('return_temp_max')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_temp_validation');
	}

}
