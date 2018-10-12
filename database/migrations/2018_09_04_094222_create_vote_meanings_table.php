<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteMeaningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_meanings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('define_meaning_id')->unsigned()->nullable();
            $table->foreign('define_meaning_id')->references('id')->on('define_meanings')->onDelete('cascade');
            $table->integer('is_poor')->nullable();
            $table->integer('vote')->nullable();
            $table->integer('context_id');
            $table->integer('phrase_id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote_meanings');
    }
}
