<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyTransaction extends Model
{
    protected $table = "daily_transactions";
    protected $fillable = ['transaction_time'];
    protected $hidden = [];
}
