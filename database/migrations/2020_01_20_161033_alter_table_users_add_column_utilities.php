<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersAddColumnUtilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->float('sound')->default(1);
            $table->tinyInteger('cheering_voice')->default(0);
            $table->tinyInteger('lamp_genie')->default(0);
            $table->tinyInteger('my_gender')->default(0);
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
            $table->dropColumn('sound');
            $table->dropColumn('cheering_voice');
            $table->dropColumn('lamp_genie');
            $table->dropColumn('my_gender');
        });
    }
}
