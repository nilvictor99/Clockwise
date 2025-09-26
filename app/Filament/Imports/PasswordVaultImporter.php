<?php

namespace App\Filament\Imports;

use App\Models\PasswordVault;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PasswordVaultImporter extends Importer
{
    protected static ?string $model = PasswordVault::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['nullable', 'integer']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('username')
                ->rules(['max:255']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('url')
                ->rules(['max:255']),
            ImportColumn::make('notes'),
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('active')
                ->requiredMapping()
                ->rules(['boolean'])
                ->castStateUsing(fn ($state) => $state === null || $state === '' ? 0 : (int) $state),
        ];
    }

    public function resolveRecord(): ?PasswordVault
    {
        // return PasswordVault::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new PasswordVault;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your password vault import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
