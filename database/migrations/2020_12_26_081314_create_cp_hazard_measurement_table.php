<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpHazardMeasurementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_hazard_measurement', function(Blueprint $table)
		{
			$table->smallInteger('id', true);
			$table->string('description')->nullable();
			$table->smallInteger('dropdown_item_id')->nullable()->default(0)->index('GroupbyList');
			$table->boolean('order')->nullable()->default(0);
			$table->boolean('score')->nullable()->default(0);
			$table->boolean('other')->nullable()->default(0);
			$table->boolean('decommissioned')->nullable()->default(0);
			$table->smallInteger('parent_id')->nullable()->default(0)->index('Groupbyparent');
			$table->decimal('removal_cost', 5)->nullable();
			$table->integer('deleted_by')->nullable();
			$table->integer('created_by')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_hazard_measurement');
	}

}
