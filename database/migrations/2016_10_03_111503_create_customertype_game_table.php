<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomertypeGameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('customer_type_game', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('code_type');
            $table->integer('game_id');
            $table->double('exchange_rates',15,2);
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
        Schema::drop('customer_type_game');
	}

}
