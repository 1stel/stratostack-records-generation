<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsageVmsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('usage_vms', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('zoneId', 40);
            $table->string('accountId', 40);
            $table->string('domainId', 40);
			$table->string('vm_name');
            $table->double('usage');
            $table->string('vmInstanceId', 40)->nullable();
            $table->string('serviceOfferingId', 40)->nullable();
            $table->string('templateId', 40)->nullable();
            $table->tinyInteger('cpuNumber');
            $table->integer('cpuSpeed');
            $table->integer('memory');
            $table->dateTime('startDate');
            $table->dateTime('endDate');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('usage_vms');
	}

}
