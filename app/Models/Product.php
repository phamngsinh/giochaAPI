<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseModels
{


    protected $table = "products";
    protected $fillable = ['name','price','description','creator'];
    protected $dates = ['created_at','updated_at'];
    public static $rules = [
        "name" => "required|min:6",
        "price" => "required|min:6|numeric",
    ];
    public static $messages = [
        'unique' => 'The :attribute has already existed',
        'required' => 'The :attribute is required',
    ];
    public function user(){
        $this->belongsTo('App\Models\User','creator','id');
    }
    public function dailyTransactions(){
        return $this->belongsToMany('App\Models\DailyTransaction','daily_transactions_products','product_id','daily_transaction_id');
    }


}
