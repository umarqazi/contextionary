<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextMarathon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('context_marathon', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('context_id');
            $table->unsignedInteger('phrase_id');
            $table->integer('bucket');
            $table->string('hint_1')->nullable();
            $table->string('hint_2')->nullable();
            $table->string('hint_3')->nullable();
            $table->string('hint_4')->nullable();
            $table->string('hint_5')->nullable();
            $table->string('hint_6')->nullable();
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
        Schema::dropIfExists('context_marathon');
    }
}
