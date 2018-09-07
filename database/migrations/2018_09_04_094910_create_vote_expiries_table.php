<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteExpiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_expiries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('context_id');
            $table->integer('phrase_id');
            $table->string('vote_type');
            $table->timestamp('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote_expiries');
    }
}
