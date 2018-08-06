<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('price');
            $table->timestamps();
        });
        Schema::create('points', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('sub_points');
            $table->timestamps();
        });
        Schema::create('packages_points', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id')->unsigned();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->integer('point_id')->unsigned();
            $table->foreign('point_id')->references('id')->on('points')->onDelete('cascade');
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
        Schema::table('package_point', function (Blueprint $table) {
            $table->dropForeign('package_point_package_id_foreign');
            $table->dropForeign('package_point_point_id_foreign');
        });
        Schema::dropIfExists('package_point');
        Schema::dropIfExists('package');
        Schema::dropIfExists('points');
    }
}
