<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class User extends BaseModels implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use SoftDeletes;
    protected $table = 'users';
    protected $hidden = ['password', 'remember_token'];
    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at','deleted_at'];
    /**
     * @var array
     */
    public static $rules = [
        "email" => "required|email|unique:users,email,:id",
        "password" => "min:6",
        "name" => "required|min:3",
        "role" => "required|numeric",
    ];
    /**
     * @var array
     */
    public static $messages = [
        'unique' => 'The :attribute has already existed',
        'required' => 'The :attribute is required',
        'email' => 'Please enter a valid email  address',
    ];

    /**
     * @param bool|false $id
     * @return array
     */
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

    /**
     *
     */
    const ADMIN_ROLE = 1;
    /**
     *
     */
    const USER_ROLE = 2;


    /**
     *
     */
    public function products()
    {
       return  $this->hasMany('App\Models\Product', 'creator', 'id');
    }

    /**
     *
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id', 'id');
    }

    /**
     * @param $pass
     */
    public function setPasswordAttribute($pass)
    {
        return $this->attributes['password'] = Hash::make($pass);
    }


}
