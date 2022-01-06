<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerLogoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_logo', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableTimestamps();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->string('file');
            $table->char('uuid', 36);
            $table->string('extension');
            $table->string('size');
            $table->integer('order')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);

            $table->integer('partner_id')->unsigned();
            $table->index('partner_id');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('partner_logo');
    }
}
