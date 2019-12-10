<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToContextMarathonStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->table('context_marathon_statistics', function (Blueprint $table) {
            $table->integer('win_in_a_row')->nullable();
            $table->integer('hint_in_a_row')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('win_in_a_row');
            $table->dropColumn('hint_in_a_row');
        });
    }
}
