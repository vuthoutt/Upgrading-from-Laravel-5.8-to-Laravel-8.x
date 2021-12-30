<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCpEquipmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cp_equipments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('property_id')->nullable()->index('property_id');
			$table->integer('assess_id')->nullable()->index('assess_id');
			$table->integer('area_id')->nullable()->index('area_id');
			$table->integer('location_id')->nullable()->index('location_id');
			$table->integer('type')->nullable()->index('type');
			$table->boolean('decommissioned')->nullable()->index('decommissioned');
			$table->boolean('nonconformities')->nullable()->index('nonconformities');
			$table->boolean('state')->nullable()->index('state');
			$table->integer('reason')->nullable();
			$table->string('reason_other')->nullable();
			$table->integer('parent_id')->nullable()->index('parent_id');
			$table->integer('system_id')->nullable()->index('system_id');
			$table->integer('frequency_use')->nullable();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
			$table->timestamps();
			$table->integer('operational_use')->nullable();
			$table->string('name')->nullable();
			$table->integer('record_id')->nullable();
			$table->string('reference')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cp_equipments');
	}

}
