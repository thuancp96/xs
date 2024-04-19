<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwocolumnCustomertypegame extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('customer_type_game', function(Blueprint $table)
        {
            $table->double('max_point',15,2);
            $table->double('max_point_one',15,2);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('customer_type_game', function(Blueprint $table)
        {
            $table->dropColumn('max_point');
            $table->dropColumn('max_point_one');

        });
	}

}
