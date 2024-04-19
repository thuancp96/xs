<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetquasosoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
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

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('ketquasoso');
	}

}
