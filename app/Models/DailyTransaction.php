<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTransaction extends BaseModels
{
    protected $dateFormat = "U";
    protected $table = "daily_transactions";
    protected $fillable = ['transaction_time'];
    protected $hidden = [];
    protected $dates = ['transaction_time','created_at','updated_at'];
    public static $rules = [
        "transaction_time" => "required|numeric",
    ];
    public static $messages = [
        'required' => 'The :attribute is required',
        'numeric' => 'The :attribute is numeric',
    ];

    public function products(){
        return $this->belongsToMany('App\Models\Product','daily_transactions_products','daily_transaction_id','product_id');
    }
    public function orders(){
        return $this->hasMany('App\Models\Order','daily_transaction_id','id');
    }
}
