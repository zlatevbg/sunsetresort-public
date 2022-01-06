<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_images', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name')->nullable();
            $table->text('content')->nullable();
            $table->string('file');
            $table->char('uuid', 36);
            $table->string('extension');
            $table->string('size');
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);

            $table->integer('award_id')->unsigned();
            $table->index('award_id');
            $table->foreign('award_id')->references('id')->on('awards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('award_images');
    }
}
