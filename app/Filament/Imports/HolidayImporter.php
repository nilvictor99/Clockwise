<?php

namespace App\Filament\Imports;

use App\Models\Holiday;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class HolidayImporter extends Importer
{
    protected static ?string $model = Holiday::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('calendar')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('staff_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['nullable']),
            ImportColumn::make('user_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['nullable']),
            ImportColumn::make('start_day')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('end_day')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('description')
                ->requiredMapping()
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): ?Holiday
    {
        // return Holiday::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Holiday;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your holiday import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
