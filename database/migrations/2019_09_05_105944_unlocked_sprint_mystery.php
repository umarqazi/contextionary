<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnlockedSprintMystery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('unlocked_sprint_mystery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('unlocked_sprint')->nullable();
            $table->integer('unlocked_mystery_topic')->nullable();
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
        Schema::dropIfExists('unlocked_sprint_mystery');
    }
}
