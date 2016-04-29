<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('set foreign_key_checks=0');

        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->double('price');
            $table->text('description');
            $table->integer('creator')->unsigned();
            $table->foreign('creator')->references('id')->on('users');
            $table->integer('created_at');
            $table->integer('quantity');
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
        Schema::drop('products');
    }
}
