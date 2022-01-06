<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->softDeletes();
            $table->string('locale');
            $table->timestamp('from')->nullable();
            $table->timestamp('to')->nullable();
            $table->tinyInteger('nights');
            $table->text('rooms');
            $table->text('roomsArray');
            $table->text('viewsArray');
            $table->text('mealsArray');
            $table->decimal('price', 10, 2)->unsigned();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('eik')->nullable();
            $table->string('vat')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('mol')->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('transactionType')->nullable()->unsigned();
            $table->string('transactionDate', 14)->nullable();
            $table->bigInteger('transactionAmount')->nullable()->unsigned();
            $table->string('transactionTerminal', 8)->nullable();
            $table->string('transactionDescription', 125)->nullable();
            $table->string('transactionLanguage', 2)->nullable();
            $table->string('transactionVersion', 3)->nullable();
            $table->string('transactionCurrency', 3)->nullable();
            $table->binary('transactionSignatureSent')->nullable();
            $table->string('transactionCode', 2)->nullable();
            $table->binary('transactionSignatureReceived')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookings');
    }
}
