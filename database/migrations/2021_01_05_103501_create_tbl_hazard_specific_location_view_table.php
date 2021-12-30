<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTblHazardSpecificLocationViewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_hazard_specific_location_view', function(Blueprint $table)
		{
			$table->integer('hazard_id')->default(0)->unique('hazard_id_index');
			$table->text('specific_location', 16777215)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_hazard_specific_location_view');
	}

}
