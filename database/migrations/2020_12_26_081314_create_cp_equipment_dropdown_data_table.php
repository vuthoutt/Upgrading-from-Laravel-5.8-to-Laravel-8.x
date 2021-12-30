<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentDropdownDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipment_dropdown_data', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('dropdown_id')->nullable();
			$table->string('description')->nullable();
			$table->boolean('other')->nullable();
			$table->integer('parent_id')->nullable();
			$table->integer('order')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_equipment_dropdown_data');
	}

}
