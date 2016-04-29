<?php

namespace App\Models;


use Illuminate\Support\Facades\Hash;

class User extends \App\User
{

    protected $fillable = ['name', 'email', 'password', 'role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
    public static $rules = [
        "email" => "required|email|unique:users,email,:id",
        "password" => "min:6",
        "name" => "required|min:3",
        "role" => "required|numeric",
    ];
    public static $messages = [
        'unique' => 'The :attribute has already existed',
        'required' => 'The :attribute is required',
        'email' => 'Please enter a valid email  address',
    ];

    public static function rules($id = false)
    {
        $rules = self::$rules;
        if ($id) {
            foreach ($rules as &$rule) {
                $rule = str_replace(':id', $id, $rule);
            }
        }
        return $rules;
    }

    const ADMIN_ROLE = 1;
    const USER_ROLE = 2;


    public function products()
    {
        $this->hasMany('App\Models\Product', 'creator', 'id');
    }

    public function orders()
    {
        $this->hasMany('App\Models\Order', 'user_id', 'id');
    }

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }


}
