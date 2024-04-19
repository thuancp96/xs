<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreateduserToCustomerGame extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('customer_type_game', function(Blueprint $table)
        {
            $table->double('odds',15,2);
            $table->integer('created_user');
            $table->boolean('change_odds');
            $table->boolean('change_ex');
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
            $table->dropColumn('odds');
            $table->dropColumn('created_user');
            $table->dropColumn('change_odds');
            $table->dropColumn('change_ex');
        });
	}

}
