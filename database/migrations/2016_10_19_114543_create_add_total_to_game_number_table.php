<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddTotalToGameNumberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('game_number', function(Blueprint $table)
        {
            $table->double('total',15,2);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('game_number', function(Blueprint $table)
        {
            $table->dropColumn('total');
        });
	}

}
