<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTranslationKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote_meanings', function (Blueprint $table) {
            $table->integer('translation_id')->unsigned()->nullable();
            $table->foreign('translation_id')->references('id')->on('translations')->onDelete('cascade');
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
            $table->dropForeign('vote_meanings_translation_id_foreign');
            $table->dropColumn('translation_id');
        });
    }
}
