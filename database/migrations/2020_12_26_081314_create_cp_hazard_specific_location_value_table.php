<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpHazardSpecificLocationValueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_hazard_specific_location_value', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('hazard_id')->nullable()->index('GroupByItemDetail');
			$table->integer('parent_id')->nullable();
			$table->integer('value')->nullable();
			$table->string('other', 500)->nullable();
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
		Schema::drop('cp_hazard_specific_location_value');
	}

}
