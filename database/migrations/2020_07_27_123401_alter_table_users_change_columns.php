<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersChangeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {

            $table->tinyInteger('finish_all_hot_context')->default(0);
            $table->tinyInteger('need_to_show_again')->default(0);
            $table->string('previous_target_word')->nullable();
            $table->integer('top_maze_level')->default(1);
            $table->integer('butterfly_effect')->default(1)->change();
            $table->integer('aladdin_lamp')->default(1)->change();
            $table->integer('stopwatch')->default(2)->change();
            $table->integer('time_traveller')->default(3)->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
