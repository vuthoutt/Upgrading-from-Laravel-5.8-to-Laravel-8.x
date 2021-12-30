<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentNonconformitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipment_nonconformities', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('reference')->nullable();
			$table->integer('record_id')->nullable();
			$table->integer('property_id')->nullable();
			$table->integer('assess_id')->nullable();
			$table->integer('equipment_id')->nullable();
			$table->integer('hazard_id')->nullable();
			$table->string('field')->nullable();
			$table->string('type')->nullable();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->boolean('is_deleted')->default(0);
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
		Schema::drop('cp_equipment_nonconformities');
	}

}
