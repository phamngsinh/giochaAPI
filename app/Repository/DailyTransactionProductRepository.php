<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class DailyTransactionProductRepository extends BaseRepository
{


    public function model()
    {
        return 'App\Models\DailyTransactionProduct';
    }
}