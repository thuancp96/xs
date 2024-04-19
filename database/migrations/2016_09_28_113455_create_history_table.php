<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('history', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('date');
            $table->string('type');
            $table->string('content');
            $table->integer('user_create');//Nguoi tao
            $table->rememberToken();
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
        Schema::drop('history');
	}

}
