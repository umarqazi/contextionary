<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlossaryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glossary_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('price');
            $table->text('description')->nullable();
            $table->string('context');
            $table->string('thumbnail');
            $table->string('file');
            $table->string('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('glossary_items');
    }
}
