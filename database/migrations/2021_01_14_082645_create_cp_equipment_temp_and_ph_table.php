<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentTempAndPhTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipment_temp_and_ph', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('equipment_id')->nullable()->index('equipment_id');
			$table->string('return_temp')->nullable();
			$table->string('flow_temp')->nullable();
			$table->string('inlet_temp')->nullable();
			$table->string('stored_temp')->nullable();
			$table->string('top_temp')->nullable();
			$table->string('bottom_temp')->nullable();
			$table->string('ph')->nullable();
			$table->string('flow_temp_gauge_value')->nullable();
			$table->string('return_temp_gauge_value')->nullable();
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
		Schema::drop('cp_equipment_temp_and_ph');
	}

}
