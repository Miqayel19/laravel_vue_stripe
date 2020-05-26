<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userID')->unsigned();
            $table->integer('accountID')->unsigned();
            $table->integer('price');
            $table->string('currency');
            $table->string('status');
            $table->dateTime('date');
        });
        Schema::table('orders', function($table) {
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
        Schema::dropIfExists('orders');
    }
}
