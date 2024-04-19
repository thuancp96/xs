<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKetquaXosoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('xoso_result', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('location_id');
            $table->string('DB');
            $table->string('Giai_1');
            $table->string('Giai_2');
            $table->string('Giai_3');
            $table->string('Giai_4');
            $table->string('Giai_5');
            $table->string('Giai_6');
            $table->string('Giai_7');
            $table->string('Giai_8');
            $table->date('date');
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
		Schema::drop('xoso_result');
	}

}
