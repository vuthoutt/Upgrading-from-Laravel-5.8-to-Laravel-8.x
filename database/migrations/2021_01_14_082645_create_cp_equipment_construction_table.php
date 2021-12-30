<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentConstructionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipment_construction', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('equipment_id')->nullable()->index('equipment_id');
			$table->boolean('anti_stratification')->nullable();
			$table->boolean('can_isolated')->nullable();
			$table->integer('direct_fired')->nullable();
			$table->boolean('flexible_hose')->nullable();
			$table->boolean('horizontal_vertical')->nullable();
			$table->boolean('water_softener')->nullable();
			$table->string('insulation_type')->nullable();
			$table->boolean('rodent_protection')->nullable();
			$table->boolean('sentinel')->nullable();
			$table->boolean('system_recirculated')->nullable();
			$table->boolean('tank_lid')->nullable();
			$table->boolean('tmv_fitted')->nullable();
			$table->boolean('warning_pipe')->nullable();
			$table->string('labelling')->nullable();
			$table->string('aerosol_risk')->nullable();
			$table->string('pipe_insulation')->nullable();
			$table->string('pipe_insulation_condition')->nullable();
			$table->string('construction_material')->nullable();
			$table->string('insulation_thickness')->nullable();
			$table->string('insulation_condition')->nullable();
			$table->boolean('drain_valve')->nullable();
			$table->string('source')->nullable();
			$table->string('source_accessibility')->nullable();
			$table->string('source_condition')->nullable();
			$table->boolean('air_vent')->nullable();
			$table->boolean('main_access_hatch')->nullable();
			$table->boolean('ball_valve_hatch')->nullable();
			$table->boolean('flow_temp_gauge')->nullable();
			$table->boolean('return_temp_gauge')->nullable();
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
		Schema::drop('cp_equipment_construction');
	}

}
