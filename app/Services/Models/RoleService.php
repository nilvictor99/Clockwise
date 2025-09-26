<?php

namespace App\Services\Models;

use App\Models\User;
use App\Repositories\Models\RoleRepository;
use App\Services\Auth\AuthService;

class RoleService extends BaseService
{
    protected $authService;

    public function __construct(RoleRepository $roleRepository, AuthService $authService)
    {
        parent::__construct($roleRepository);
        $this->authService = $authService;
    }

    public function getPermissions(User $user)
    {
        return $user->roles()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->unique();
    }

    public function getRoleNames(User $user)
    {
        return $user->roles()->pluck('name')->unique();
    }
}
