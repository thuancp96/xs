<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAxToGameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('games', function(Blueprint $table)
        {
            $table->double('a',15,2);
            $table->double('x',15,2);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('games', function(Blueprint $table)
        {
            $table->dropColumn('a');
            $table->dropColumn('x');
        });
	}

}
