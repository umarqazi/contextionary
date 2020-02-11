<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserCurrentContext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('user_current_context', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('current_context_id');
            $table->integer('last_played_phrase_id');
            $table->integer('last_played_cell');
            $table->integer('unlocked_context');
            $table->integer('top_maze_level');
            $table->tinyInteger('learning_center');
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
        Schema::dropIfExists('user_current_context');
    }
}
