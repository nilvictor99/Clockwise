<?php

namespace App\Observers;

use App\Models\PasswordVault;

class PasswordVaultObserver
{
    public function creating(PasswordVault $passwordVault): void
    {
        // $passwordVault->password = bcrypt($passwordVault->password);
    }

    public function created(PasswordVault $passwordVault): void
    {
        //
    }

    public function updated(PasswordVault $passwordVault): void
    {
        //
    }

    public function deleted(PasswordVault $passwordVault): void
    {
        //
    }

    public function restored(PasswordVault $passwordVault): void
    {
        //
    }

    public function forceDeleted(PasswordVault $passwordVault): void
    {
        //
    }
}
