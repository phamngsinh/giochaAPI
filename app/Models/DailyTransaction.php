<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DailyTransaction
 * @package App\Models
 */
class DailyTransaction extends BaseModels
{
    /**
     * @var string
     */
    protected $dateFormat = "U";
    /**
     * @var string
     */
    protected $table = "daily_transactions";
    /**
     * @var array
     */
    protected $fillable = ['transaction_time'];
    /**
     * @var array
     */
    protected $hidden = [];
    /**
     * @var array
     */
    protected $dates = ['transaction_time','created_at','updated_at'];
    /**
     * @var array
     */
    public static $rules = [
        "transaction_time" => "required|numeric",
        "product_id" => "required|numeric",
    ];
    /**
     * @var array
     */
    public static $messages = [
        'required' => 'The :attribute is required',
        'numeric' => 'The :attribute is numeric',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(){
        return $this->belongsToMany('App\Models\Product','daily_transactions_products','daily_transaction_id','product_id')->withPivot('quantity');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(){
        return $this->hasMany('App\Models\Order','daily_transaction_id','id');
    }

}
