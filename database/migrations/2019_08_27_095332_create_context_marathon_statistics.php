<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextMarathonStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('context_marathon_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('context_id');
            $table->integer('points');
            $table->integer('bucket');
            $table->integer('status');
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
        Schema::dropIfExists('context_marathon_statistics');
    }
}
