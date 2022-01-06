<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoIconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_icon', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('file');
            $table->char('uuid', 36);
            $table->string('extension');
            $table->string('size');

            $table->integer('info_id')->unsigned();
            $table->index('info_id');
            $table->foreign('info_id')->references('id')->on('info')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('info_icon');
    }
}
