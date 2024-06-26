<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CraeteLocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->integer('parent_id')->default(0);
            $table->integer('order');
            $table->boolean('active');
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
		Schema::drop('location');
	}

}
