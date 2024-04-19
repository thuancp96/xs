<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwocolCustomertypegame extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_type_game', function(Blueprint $table)
        {
            $table->boolean('change_max');
            $table->boolean('change_max_one');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_type_game', function(Blueprint $table)
        {
            $table->dropColumn('change_max');
            $table->dropColumn('change_max_one');

        });
    }
}
