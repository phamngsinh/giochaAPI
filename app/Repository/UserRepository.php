<?php
namespace App\Repository;

use Bosnadev\Repositories\Eloquent\Repository;
use Bosnadev\Repositories\Contracts\RepositoryInterface;

class UserRepository extends BaseRepository
{


    public function model()
    {
        return 'App\Models\User';
    }
}