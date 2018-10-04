<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVoteMeaningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote_meanings', function (Blueprint $table) {
            $table->integer('illustrator_id')->unsigned()->nullable();
            $table->foreign('illustrator_id')->references('id')->on('illustrators')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vote_meanings', function (Blueprint $table) {
            $table->dropForeign('vote_meanings_illustrator_id_foreign');
            $table->dropColumn('illustrator_id');
        });
    }
}
