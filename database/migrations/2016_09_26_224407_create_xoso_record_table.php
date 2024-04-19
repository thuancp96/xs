<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXosoRecordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('xoso_record', function(Blueprint $table)
        {
            $table->increments('id');
            $table->date('date');
            $table->integer('user_id');
            $table->integer('game_id');
            $table->double('total_bet_money',15,2);//Tong so tien dat cuoc
            $table->double('bet_money_per_number',15,2);//So tien dat cuoc 1 con
            $table->double('win_money_per_number',15,2);//So tien thang tren 1 con
            $table->double('odds',15,2);//Tỉ lệ đặt cược
            $table->double('exchange_rates',15,2);//Tỉ giá 1 con ví dụ lô là đánh 1 con phải là 1kx22
            $table->double('total_win_money',15,2);//Tong so tien thang
            $table->string('bet_number');
            $table->string('win_number');
            $table->boolean('isDelete');
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
