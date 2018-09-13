<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefineMeaningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('define_meanings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('meaning');
            $table->integer('context_id');
            $table->integer('phrase_id');
            $table->string('phrase_type');
            $table->integer('position')->nullable();
            $table->integer('coins')->nullable();
            $table->integer('status');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('define_meanings');
    }
}
