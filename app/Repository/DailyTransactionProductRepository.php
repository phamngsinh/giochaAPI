<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class DailyTransactionProductRepository extends Repository
{


    public function model()
    {
        return 'App\Models\DailyTransactionProduct';
    }
}