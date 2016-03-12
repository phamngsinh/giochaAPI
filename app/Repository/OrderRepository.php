<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class OrderRepository extends Repository
{


    public function model()
    {
        return 'App\Models\Order';
    }
}