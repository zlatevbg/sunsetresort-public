<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailabilityPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->timestamp('dfrom')->nullable();
            $table->timestamp('dto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('availability_periods');
    }
}
