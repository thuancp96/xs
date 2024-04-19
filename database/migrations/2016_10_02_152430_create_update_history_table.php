<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('history');
        Schema::create('history', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('date');
            $table->string('type');
            $table->string('content');
            $table->double('money',15,2);
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
