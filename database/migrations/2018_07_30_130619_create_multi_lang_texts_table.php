<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMultiLangTextsTable extends Migration
{
    protected $table;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->table = 'texts';

    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->char('key');
            $table->char('lang', 2);
            $table->longText('value');
            $table->enum('scope', ['admin', 'site', 'global'])->default('global');
            $table->timestamps();
            $table->unique(['key', 'lang', 'scope']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table);
    }

}
