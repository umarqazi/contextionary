<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserCurrentContextAddColumnNoOfHintsUsed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->table('user_current_context', function (Blueprint $table) {
            $table->integer('no_of_hints_used')->after('top_maze_level')->default(0);
            $table->tinyInteger('crystal_ball_used')->after('no_of_hints_used')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('pgsql')->table('user_current_context', function (Blueprint $table) {
            $table->dropColumn('no_of_hints_used');
            $table->dropColumn('crystal_ball_used');
        });
    }
}
