<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentCleaningTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipment_cleaning', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('equipment_id')->nullable();
			$table->string('operational_exposure')->nullable();
			$table->integer('envidence_stagnation')->nullable();
			$table->string('degree_fouling')->nullable();
			$table->string('degree_biological')->nullable();
			$table->string('extent_corrosion')->nullable();
			$table->string('cleanliness')->nullable();
			$table->string('ease_cleaning')->nullable();
			$table->string('comments', 500)->nullable();
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
		Schema::drop('cp_equipment_cleaning');
	}

}
