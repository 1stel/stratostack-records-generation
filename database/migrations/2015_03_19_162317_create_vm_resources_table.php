<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVmResourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vm_resources', function(Blueprint $table)
		{
            $table->bigInteger('vmInstanceId')->unsigned()->unique();
            $table->tinyInteger('cpuNumber');
            $table->integer('cpuSpeed');
            $table->integer('memory');
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
		Schema::drop('vm_resources');
	}

}
