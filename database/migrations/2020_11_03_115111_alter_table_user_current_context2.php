<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserCurrentContext2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->table('user_current_context', function (Blueprint $table) {
            $table->integer('normal_hint_count')->default(0);
            $table->tinyInteger('golden_revealer')->default(0);
            $table->integer('golden_revealer_count')->default(0);
            $table->tinyInteger('diamond_revealer')->default(0);
            $table->tinyInteger('golden_hints')->default(0);
            $table->tinyInteger('diamond_hints')->default(0);
            $table->integer('normal_revealer_usage_count')->default(0);
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
            $table->dropColumn('normal_hint_count');
            $table->dropColumn('golden_revealer');
            $table->dropColumn('golden_revealer_count');
            $table->dropColumn('diamond_revealer');
            $table->dropColumn('golden_hints');
            $table->dropColumn('diamond_hints');
            $table->dropColumn('normal_revealer_usage_count');
        });
    }
}
