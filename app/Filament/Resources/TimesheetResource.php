<?php

namespace App\Filament\Resources;

use App\Filament\Exports\TimesheetExporter;
use App\Filament\Imports\TimesheetImporter;
use App\Filament\Resources\TimesheetResource\Pages;
use App\Models\Timesheet;
use App\Services\Utils\TimesheetDateService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation-panel.Logistics');
    }

    public static function getNavigationLabel(): string
    {
        return __('timesheets.navegation_label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('timesheets.navegation_label');
    }

    public static function getModelLabel(): string
    {
        return __('timesheets.navegation_label_singel');
    }

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('staff.name')
                    ->numeric()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __($state))
                    ->color(fn (string $state): string => match ($state) {
                        'break' => 'success',
                        'work' => 'info',
                        default => 'gray',
                    })
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('day_in')
                    ->label('Entrada')
                    ->time()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('day_out')
                    ->label('Salida')
                    ->time()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('work_date')
                    ->translateLabel()
                    ->getStateUsing(fn ($record) => (new TimesheetDateService)->formatWorkDate($record->day_in, $record->day_out))
                    ->sortable(),
                Tables\Columns\TextColumn::make('hours')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exporter(TimesheetExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(TimesheetImporter::class)
                    ->icon('heroicon-o-arrow-up-tray'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheets::route('/'),
            // 'create' => Pages\CreateTimesheet::route('/create'),
            'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}
