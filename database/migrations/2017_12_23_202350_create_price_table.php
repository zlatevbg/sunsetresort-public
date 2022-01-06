<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->decimal('price', 6, 2)->nullable()->unsigned();
            $table->tinyInteger('discount')->nullable()->unsigned();

            $table->integer('period_id')->unsigned();
            $table->index('period_id');
            $table->foreign('period_id')->references('id')->on('price_periods')->onDelete('cascade');

            $table->integer('view')->unsigned();
            $table->index('view');
            $table->foreign('view')->references('id')->on('views')->onDelete('cascade');

            $table->integer('meal')->unsigned();
            $table->index('meal');
            $table->foreign('meal')->references('id')->on('meals')->onDelete('cascade');

            $table->integer('room')->unsigned();
            $table->index('room');
            $table->foreign('room')->references('id')->on('rooms')->onDelete('cascade');

            $table->unique(['period_id', 'room', 'view', 'meal']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('prices');
    }
}
