<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * @package App\Models
 */
class Order extends BaseModels
{
    /**
     * @var string
     */
    protected $dateFormat = "U";
    /**
     * @var string
     */
    protected $table = "orders";
    /**
     * @var array
     */
    protected $fillable = ['note','status','user_id','daily_transaction_product_id'];
    /**
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    /**
     * @var array
     */
    public static $rules = [
        "note" => "required",
        "user_id" => "required",
        "status" => "required|numeric",
        "product_id" => "required|numeric",
        "quantity" => "required|numeric",
    ];
    /**
     * @var array
     */
    public static $messages = [
        'required' => 'The :attribute is required',
    ];

    const ORDER_ACTIVE = 1;
    const ORDER_CANCEL = 2;
    const ORDER_VERIFY = 3;
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dailyTransactions(){
        return $this->belongsTo('App\Models\DailyTransaction','daily_transaction_d');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users(){
        return $this->belongsTo('App\Models\User','user_id');
    }

}
