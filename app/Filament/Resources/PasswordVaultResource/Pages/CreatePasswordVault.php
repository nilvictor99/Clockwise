<?php

namespace App\Filament\Resources\PasswordVaultResource\Pages;

use App\Filament\Resources\PasswordVaultResource;
use App\Services\Auth\AuthService;
use Filament\Resources\Pages\CreateRecord;

class CreatePasswordVault extends CreateRecord
{
    protected static string $resource = PasswordVaultResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = app(AuthService::class)->getAuthUser()->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
