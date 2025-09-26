<?php

namespace App\Services\Models;

use App\Repositories\Models\PasswordShareRepository;

class PasswordShareService extends BaseService
{
    public function __construct(PasswordShareRepository $repository)
    {
        parent::__construct($repository);
    }
}
