<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewcolumnToXosorecordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('xoso_record', function(Blueprint $table)
        {
            $table->integer('xien_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('xoso_record', function(Blueprint $table)
        {
            $table->dropColumn('xien_id');
        });
	}

}
