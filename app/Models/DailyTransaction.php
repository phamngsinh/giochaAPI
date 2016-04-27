<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTransaction extends BaseModels
{
    protected $dateFormat = "U";
    protected $table = "daily_transactions";
    protected $fillable = ['transaction_time'];
    protected $hidden = [];
}
