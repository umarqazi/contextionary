<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsers1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->integer('max_unlocked_context')->default(1);
            $table->string('result_hint_index')->nullable();
            $table->string('current_letter_text')->nullable();
            $table->string('username')->after('email')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('max_unlocked_context');
            $table->dropColumn('result_hint_index');
            $table->dropColumn('current_letter_text');
            $table->dropColumn('username');
        });
    }
}
