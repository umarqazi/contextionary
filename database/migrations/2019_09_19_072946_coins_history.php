<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoinsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('coins_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('game_session');
            $table->integer('game_id');
            $table->integer('topic_id')->nullable();
            $table->integer('context_id')->nullable();
            $table->integer('mode');
            $table->integer('type');
            $table->integer('coins');
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
        Schema::dropIfExists('coins_history');
    }
}
