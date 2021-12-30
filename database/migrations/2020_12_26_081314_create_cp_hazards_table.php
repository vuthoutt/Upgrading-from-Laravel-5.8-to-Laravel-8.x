<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpHazardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_hazards', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('property_id')->nullable()->index('property_id');
			$table->integer('assess_id')->nullable()->index('assess_id');
			$table->integer('area_id')->nullable()->index('area_id');
			$table->integer('location_id')->nullable()->index('location_id');
			$table->boolean('decomissioned')->default(0)->index('decomissioned');
			$table->integer('type')->nullable()->index('type');
			$table->integer('likelihood_of_harm')->nullable();
			$table->integer('hazard_potential')->nullable();
			$table->integer('total_risk')->nullable()->index('total_risk');
			$table->string('extent')->nullable();
			$table->integer('measure_id')->nullable();
			$table->string('comment', 500)->nullable();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
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
		Schema::drop('cp_hazards');
	}

}
