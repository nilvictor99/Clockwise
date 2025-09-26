<?php

namespace App\Services\Models;

use App\Repositories\Models\PasswordVaultRepository;

class PasswordVaultService extends BaseService
{
    public function __construct(PasswordVaultRepository $repository)
    {
        parent::__construct($repository);
    }
}
