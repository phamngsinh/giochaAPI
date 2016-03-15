<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTransactionsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('set foreign_key_checks=0');
        Schema::create('daily_transactions_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('daily_transaction_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('daily_transaction_id')->references('id')->on('daily_transactions');
            $table->timestamps();
        });
        DB::statement('set foreign_key_checks=1');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('daily_transactions_products');
    }
}
