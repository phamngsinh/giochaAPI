<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTransactionProduct extends BaseModels
{
    protected $dateFormat = "U";
    protected $table = "daily_transactions_products";
    protected $fillable = ['product_id','daily_transaction_id'];

}
