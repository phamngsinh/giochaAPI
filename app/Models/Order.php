<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends BaseModels
{
    protected $dateFormat = "U";
    protected $table = "orders";
    protected $fillable = ['note','status','user_id','daily_transaction_product_id'];
    protected $dates = ['created_at','updated_at'];
    public static $rules = [
        "note" => "required",
        "user_id" => "required",
        "status" => "numeric",
        "daily_transaction_product_id" => "required",
    ];
    public static $messages = [
        'required' => 'The :attribute is required',
    ];
    public function dailyTransactions(){
        return $this->belongsTo('App\Models\DailyTransaction','daily_transaction_d','id');
    }

}
