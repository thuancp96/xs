<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableKetquasoso extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('ketquasoso');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::create('ketquasoso', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('giatri');
            $table->dateTime('ngay');
            $table->integer('locationId');
            $table->integer('stt');
        });
	}

}
