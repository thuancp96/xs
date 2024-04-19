<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('games');
        Schema::create('games', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->integer('order');
            $table->boolean('active');
            $table->integer('location_id');
            $table->integer('parent_id')->default(0);
            $table->string('game_guide');
            $table->double('odds',15,2);//Ví dụ 1:80
            $table->double('exchange_rates',15,2);//Tỉ giá 1 con ví dụ lô là đánh 1 con phải là 1kx22
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
		//
	}

}
