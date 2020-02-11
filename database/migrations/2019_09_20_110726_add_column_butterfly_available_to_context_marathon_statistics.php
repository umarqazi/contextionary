<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnButterflyAvailableToContextMarathonStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->table('context_marathon_statistics', function (Blueprint $table){
            $table->integer('butterfly_available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('context_marathon_statistics', function($table) {
            $table->dropColumn('butterfly_available');
        });
    }
}
