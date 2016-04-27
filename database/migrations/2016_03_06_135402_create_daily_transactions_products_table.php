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
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=0');
        Schema::create('daily_transactions_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('daily_transaction_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');
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
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=0');
        Schema::drop('daily_transactions_products');
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=1');
    }
}
