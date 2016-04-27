<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=0');
        Schema::create('daily_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('transaction_time');
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
        Schema::drop('daily_transactions');
    }
}
