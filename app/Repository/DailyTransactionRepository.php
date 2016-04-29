<?php
namespace App\Repository;

use App\Models\DailyTransaction;
use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DailyTransactionRepository extends BaseRepository
{


    public function model()
    {
        return 'App\Models\DailyTransaction';
    }
    public function getTransactionTime(){
        return  DailyTransaction::where('transaction_time','>',Carbon::now()->subDay()->timestamp)
            ->where('transaction_time','<',Carbon::now()->addDays(2)->timestamp)->first();
    }
}