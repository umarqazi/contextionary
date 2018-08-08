<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMultiLangAddColumnId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('texts');
        Schema::create('texts', function (Blueprint $table) {
            $table->char('key');
            $table->char('lang', 2);
            $table->longText('value');
            $table->enum('scope', ['admin', 'site', 'global'])->default('global');
            $table->timestamps();
            $table->unique(['key', 'lang', 'scope']);
        });
        Schema::table('texts', function (Blueprint $table) {
            $table->increments('id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('texts', function (Blueprint $table) {
            $table->dropColumn(['id']);
        });
    }
}
