<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('planID')->unsigned();
            $table->integer('accountID')->unsigned();
            $table->integer('userID')->unsigned();
            $table->string('period')->nullable();
        });
        Schema::table('cart_items', function($table) {
            $table->foreign('planID')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('accountID')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
