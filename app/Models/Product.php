<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $dateFormat = "U";
    protected $table = "products";
    protected $fillable = ['name','price','description','creator'];

    public function user(){
        $this->belongsTo('App\Models\User','creator','id');
    }

}
