<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseModels
{


    protected $table = "products";
    protected $fillable = ['name','price','description','creator'];

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


}
