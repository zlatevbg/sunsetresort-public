<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
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
            $table->decimal('payment', 10, 2)->unsigned()->nullable();
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
            $table->enum('status', ['used', 'refunded', 'expired', 'paid'])->nullable();
            $table->decimal('amount', 10, 2)->unsigned();
            $table->tinyInteger('type')->unsigned();
            $table->integer('order')->unsigned()->nullable();
            $table->string('description', 50)->nullable();
            $table->string('gmt', 3);
            $table->string('merchant_timestamp', 14)->nullable();
            $table->string('merchant_nonce', 32);
            $table->text('merchant_signature')->nullable();
            $table->text('minfo')->nullable();
            $table->tinyInteger('action')->unsigned()->nullable();
            $table->string('rc', 3)->nullable();
            $table->string('approval', 6)->nullable();
            $table->string('rrn', 12)->nullable();
            $table->string('int_ref', 32)->nullable();
            $table->string('status_msg')->nullable();
            $table->string('card')->nullable();
            $table->string('borica_timestamp', 14)->nullable();
            $table->string('borica_tran_date', 14)->nullable();
            $table->string('pares_status', 1)->nullable();
            $table->string('eci', 2)->nullable();
            $table->string('borica_nonce', 32)->nullable();
            $table->text('borica_signature')->nullable();

            $table->integer('user_id')->unsigned()->nullable();
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
