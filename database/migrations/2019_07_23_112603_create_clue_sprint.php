<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClueSprint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('clue_sprint', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('topic_id');
            $table->unsignedBigInteger('context_id');
            $table->unsignedBigInteger('phrase_id');
            $table->integer('word_to_replace');
            $table->unsignedBigInteger('replacement_id_1');
            $table->unsignedBigInteger('replacement_id_2');
            $table->unsignedBigInteger('replacement_id_3');
            $table->integer('bucket');
            $table->foreign('topic_id')->references('id')->on('context_topics')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('clue_sprint');
    }
}
