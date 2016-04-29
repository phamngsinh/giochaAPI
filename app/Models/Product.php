<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Models
 */
class Product extends BaseModels
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = "products";
    /**
     * @var array
     */
    protected $fillable = ['name','price','description','creator'];
    /**
     * @var array
     */
    protected $dates = ['created_at','updated_at'];
    /**
     * @var array
     */
    public static $rules = [
        "name" => "required|min:6",
        "price" => "required|min:6|numeric",
    ];
    /**
     * @var array
     */
    public static $messages = [
        'unique' => 'The :attribute has already existed',
        'required' => 'The :attribute is required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\Models\User','creator','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dailyTransactions(){
        return $this->belongsToMany('App\Models\DailyTransaction','daily_transactions_products','product_id','daily_transaction_id');
    }


}
