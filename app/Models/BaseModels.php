<?php
/**
 * Created by PhpStorm.
 * User: smagic39
 * Date: 4/26/16
 * Time: 6:53 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModels extends  Model
{
    public static $messages = [
        'unique' => 'The :attribute has already existed',
        'required' => 'The :attribute is required',
    ];

    public function getAttribute($key) {
        //echo $key, '|'; snake_case($key);
        return parent::getAttribute(snake_case($key));
    }
    public function setAttribute($key, $value) {
        //echo snake_case('first_name'), '|', camel_case('first_name'), ';';
        return parent::setAttribute(snake_case($key), $value);
    }

    public function toArray() {
        $rs = array_camel_case(parent::toArray(), false);

        return $rs;
    }
    protected $dates = [
        "created_at",
        "updated_at",
    ];
    protected $dateFormat = "U";
}