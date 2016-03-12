<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class ProductRepository extends Repository
{


    public function model()
    {
        return 'App\Models\Product';
    }
}