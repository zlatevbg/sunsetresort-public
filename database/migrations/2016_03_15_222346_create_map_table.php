<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->integer('parent')->unsigned()->nullable();
            $table->string('name');
            $table->mediumText('content')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('slug')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_category')->default(false);
            $table->string('color')->nullable();

            $table->foreign('parent')->references('id')->on('map')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('map');
    }
}
