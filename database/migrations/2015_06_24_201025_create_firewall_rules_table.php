<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirewallRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('firewall_rules', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('reseller_id')->unsigned()->nullable();
            $table->string('src', 40);
            $table->string('src_cidr', 4);
            $table->smallInteger('dst_port')->unsigned()->nullable();
            $table->enum('protocol', ['tcp', 'udp', 'icmp']);
            $table->boolean('active')->default(0);
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
		Schema::drop('firewall_rules');
	}

}
