<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends BaseModels
{
    protected $dateFormat = "U";
    protected $table = "orders";
    protected $fillable = ['note','status','user_id','daily_transaction_product_id'];
}
