<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AlterDefineMeaningsTableAddOldPhraseType
 */
class AlterDefineMeaningsTableAddOldPhraseType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('define_meanings', function(Blueprint $table) {
            $table->string('old_phrase_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('define_meanings', function($table) {
            $table->dropColumn('old_phrase_type');
        });
    }
}
