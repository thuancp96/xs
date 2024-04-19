<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameNumberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('game_number', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('code_type');
            $table->string('number');
            $table->double('a',15,2);
            $table->double('x',15,2);
            $table->double('y',15,2);
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
        Schema::drop('game_number');
	}

}
