<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_images', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('file');
            $table->char('uuid', 36);
            $table->string('extension');
            $table->string('size');

            $table->integer('room_id')->unsigned();
            $table->index('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('room_images');
    }
}
