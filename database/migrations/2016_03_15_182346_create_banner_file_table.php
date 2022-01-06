<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner_file', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('file');
            $table->char('uuid', 36);
            $table->string('extension');
            $table->string('size');

            $table->integer('banner_id')->unsigned();
            $table->index('banner_id');
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banner_file');
    }
}
