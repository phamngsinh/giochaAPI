<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class UserRepository extends Repository
{


    public function model()
    {
        return 'App\Models\User';
    }
}