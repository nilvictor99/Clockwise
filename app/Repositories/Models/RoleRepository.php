<?php

namespace App\Repositories\Models;

use App\Models\Role;

class RoleRepository extends BaseRepository
{
    const RELATIONS = [
        'permissions',
    ];

    public function __construct(Role $role)
    {
        parent::__construct($role, self::RELATIONS);
    }
}
