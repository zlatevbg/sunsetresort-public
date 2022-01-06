<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->tinyInteger('availability')->nullable()->unsigned();
            $table->tinyInteger('min_stay')->nullable()->unsigned();

            $table->integer('period_id')->unsigned();
            $table->index('period_id');
            $table->foreign('period_id')->references('id')->on('availability_periods')->onDelete('cascade');

            $table->string('room');
            $table->index('room');
            $table->foreign('room')->references('slug')->on('rooms')->onDelete('cascade');

            $table->string('view');
            $table->index('view');
            $table->foreign('view')->references('slug')->on('views')->onDelete('cascade');

            $table->unique(['period_id', 'room', 'view']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('availability');
    }
}
