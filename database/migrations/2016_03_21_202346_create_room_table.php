<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->integer('parent')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->tinyInteger('area')->unsigned()->nullable();
            $table->tinyInteger('capacity')->unsigned()->nullable();
            $table->tinyInteger('adults')->unsigned()->nullable();
            $table->tinyInteger('children')->unsigned()->nullable();
            $table->tinyInteger('infants')->unsigned()->nullable();
            $table->text('content')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_category')->default(false);

            $table->foreign('parent')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms');
    }
}
