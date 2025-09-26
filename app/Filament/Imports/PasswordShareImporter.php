<?php

namespace App\Filament\Imports;

use App\Models\PasswordShare;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PasswordShareImporter extends Importer
{
    protected static ?string $model = PasswordShare::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('password_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['nullable']),
            ImportColumn::make('shared_with')
                ->requiredMapping()
                ->numeric()
                ->rules(['nullable']),
            ImportColumn::make('shared_by')
                ->requiredMapping()
                ->numeric()
                ->rules(['nullable']),
            ImportColumn::make('permissions')
                ->requiredMapping()
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): ?PasswordShare
    {
        // return PasswordShare::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new PasswordShare;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your password share import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
