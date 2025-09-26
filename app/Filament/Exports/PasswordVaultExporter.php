<?php

namespace App\Filament\Exports;

use App\Models\PasswordVault;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PasswordVaultExporter extends Exporter
{
    protected static ?string $model = PasswordVault::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('user_id'),
            ExportColumn::make('name'),
            ExportColumn::make('username'),
            ExportColumn::make('url'),
            ExportColumn::make('notes'),
            ExportColumn::make('type'),
            ExportColumn::make('active'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your password vault export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
