<?php

namespace App\Filament\Resources\PasswordShareResource\Pages;

use App\Filament\Resources\PasswordShareResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPasswordShares extends ListRecords
{
    protected static string $resource = PasswordShareResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
