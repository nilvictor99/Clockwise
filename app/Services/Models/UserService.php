<?php

namespace App\Services\Models;

use App\Repositories\Models\UserRepository;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }
}
