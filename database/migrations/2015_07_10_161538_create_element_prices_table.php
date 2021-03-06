<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('element', '60');
            $table->smallInteger('quantity');
            $table->enum('quantity_type', ['MB', 'GB', 'TB'])->nullable();
            $table->decimal('price', 6, 5);
            $table->boolean('active')->default('1');
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
        Schema::drop('element_prices');
    }
}
