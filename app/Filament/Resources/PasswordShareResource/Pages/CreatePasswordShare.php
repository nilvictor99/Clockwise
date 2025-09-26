<?php

namespace App\Filament\Resources\PasswordShareResource\Pages;

use App\Filament\Resources\PasswordShareResource;
use App\Services\Auth\AuthService;
use Filament\Resources\Pages\CreateRecord;

class CreatePasswordShare extends CreateRecord
{
    protected static string $resource = PasswordShareResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['shared_by'] = app(AuthService::class)->getAuthUser()->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
