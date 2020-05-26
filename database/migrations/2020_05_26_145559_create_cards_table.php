<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userID')->unsigned();
            $table->integer('accountID')->unsigned();
            $table->string('fullname');
            $table->string('card_number');
            $table->integer('exp_month');
            $table->integer('exp_year');
            $table->string('last_four_digits');
            $table->dateTime('renew_date');
        });
        Schema::table('cards', function($table) {
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('accountID')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
