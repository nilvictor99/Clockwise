<?php

namespace App\Filament\Imports;

use App\Models\Timesheet;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TimesheetImporter extends Importer
{
    protected static ?string $model = Timesheet::class;

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
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('day_in')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('day_out')
                ->requiredMapping()
                ->rules(['nullable']),
            ImportColumn::make('hours'),
        ];
    }

    public function resolveRecord(): ?Timesheet
    {
        // return Timesheet::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Timesheet;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your timesheet import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
