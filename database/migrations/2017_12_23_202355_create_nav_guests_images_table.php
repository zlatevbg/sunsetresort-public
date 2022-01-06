<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavGuestsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nav_guests_images', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('file');
            $table->char('uuid', 36);
            $table->string('extension');
            $table->string('size');
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);

            $table->integer('nav_guest_id')->unsigned();
            $table->index('nav_guest_id');
            $table->foreign('nav_guest_id')->references('id')->on('nav_guests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nav_guests_images');
    }
}
