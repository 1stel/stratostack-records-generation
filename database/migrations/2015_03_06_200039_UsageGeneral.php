<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsageGeneral extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// VM Instance usage DB
		Schema::create('usage_general', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('zoneId', 40);
			$table->string('accountId', 40);
            $table->string('domainId', 40);
			$table->enum('type', ['LB', 'PF', 'VPN', 'Network Sent', 'Network Received']);
			$table->double('usage');
			$table->string('vmInstanceId', 40)->nullable();
			$table->string('templateId', 40)->nullable();
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
		// Drop the table
		Schema::drop('usage_general');
	}

}
