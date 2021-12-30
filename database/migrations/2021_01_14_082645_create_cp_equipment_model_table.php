<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentModelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipment_model', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('equipment_id')->index('equipment_id');
			$table->string('manufacturer')->nullable();
			$table->string('model')->nullable();
			$table->string('dimensions_length')->nullable();
			$table->string('dimensions_width')->nullable();
			$table->string('dimensions_depth')->nullable();
			$table->string('capacity')->nullable();
			$table->string('stored_water')->nullable();
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
		Schema::drop('cp_equipment_model');
	}

}
