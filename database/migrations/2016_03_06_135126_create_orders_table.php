<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=0');
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->text('note');
            $table->integer('status');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('daily_transaction_id')->unsigned();
            $table->foreign('daily_transaction_id')->references('id')->on('daily_transactions');
            $table->integer('created_at');
            $table->integer('updated_at');
        });
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=1');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
