<?php

namespace App\Policies;

use App\Models\PasswordVault;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PasswordVaultPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_password::vault');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PasswordVault $passwordVault): bool
    {
        return $user->can('view_password::vault');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_password::vault');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PasswordVault $passwordVault): bool
    {
        return $user->can('update_password::vault');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PasswordVault $passwordVault): bool
    {
        return $user->can('delete_password::vault');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_password::vault');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PasswordVault $passwordVault): bool
    {
        return $user->can('force_delete_password::vault');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_password::vault');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PasswordVault $passwordVault): bool
    {
        return $user->can('restore_password::vault');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_password::vault');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PasswordVault $passwordVault): bool
    {
        return $user->can('replicate_password::vault');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_password::vault');
    }
}
