<?php

namespace App\Filament\Resources\PasswordShareResource\Pages;

use App\Filament\Resources\PasswordShareResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasswordShare extends EditRecord
{
    protected static string $resource = PasswordShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();

        return $resource::getUrl('index');
    }
}
