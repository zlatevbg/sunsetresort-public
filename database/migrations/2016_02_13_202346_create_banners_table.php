<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->integer('parent')->unsigned()->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('slogan')->nullable();
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->string('identifier')->nullable();
            $table->mediumText('content')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_category')->default(false);
            $table->boolean('is_video')->default(false);

            $table->foreign('parent')->references('id')->on('banners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banners');
    }
}
