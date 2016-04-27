<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class OrderRepository extends BaseRepository
{


    public function model()
    {
        return 'App\Models\Order';
    }
}