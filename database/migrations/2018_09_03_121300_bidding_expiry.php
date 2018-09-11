<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BiddingExpiry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidding_expiry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('context_id');
            $table->integer('phrase_id');
            $table->string('bid_type');
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
        Schema::dropIfExists('bidding_expiry');
    }
}
