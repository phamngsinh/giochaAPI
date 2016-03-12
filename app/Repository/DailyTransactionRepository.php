<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class DailyTransactionRepository extends Repository
{


    public function model()
    {
        return 'App\Models\DailyTransaction';
    }
}