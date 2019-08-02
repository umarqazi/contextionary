<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGamePoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('user_game_points', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('game_id');
            $table->integer('points');
            $table->integer('level')->nullable();
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_game_points');
    }
}
