<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeplayToGameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('games', function(Blueprint $table)
        {
            $table->string('open');
            $table->string('close');
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
            $table->dropColumn('open');
            $table->dropColumn('close');
        });
	}

}
