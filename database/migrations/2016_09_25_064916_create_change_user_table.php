<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('users');
        Schema::create('users', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password', 60);
            $table->double('credit',15,2);//Tín dụng
            $table->double('consumer',15,2);//Tín dụng đã dùng
            $table->double('remain',15,2);//Tín dụng còn lại
            $table->boolean('lock');
            $table->string('fullname');
            $table->string('bet');//Tỉ lệ cược
            $table->integer('roleid');
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
		//
	}

}
