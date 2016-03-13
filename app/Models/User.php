<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends \App\User
{

    protected $fillable = ['name', 'email', 'password','first_name','last_name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public function products(){
        $this->hasMany('App\Models\Product','creator','id');
    }
    public function orders(){
        $this->hasMany('App\Models\Order','user_id','id');
    }
}
