<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->integer('parent')->unsigned()->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('price_adult', 6, 2)->nullable()->unsigned();
            $table->decimal('price_child', 6, 2)->nullable()->unsigned();
            $table->string('slug')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_category')->default(false);

            $table->foreign('parent')->references('id')->on('meals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('meals');
    }
}
